<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ActivityLog;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        // Only clients should be able to see this page
        $role = session()->get('active_role', 'client');
        if ($role !== 'client') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya client yang bisa memposting pekerjaan.');
        }

        if (!Auth::user()->is_active) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda telah dinonaktifkan karena terdeteksi adanya pelanggaran.');
        }

        return view('projects.create');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        // Only clients should be able to post jobs
        $role = session()->get('active_role', 'client');
        if ($role !== 'client') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya client yang bisa memposting pekerjaan.');
        }

        if (!Auth::user()->is_active) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda telah dinonaktifkan karena terdeteksi adanya pelanggaran.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'deadline' => 'nullable|date|after_or_equal:today',
        ]);

        $validated['client_id'] = Auth::id();
        $validated['status'] = 'pending';

        $project = Project::create($validated);

        // Optional: log this activity
        ActivityLog::create([
            'description' => 'Client ' . Auth::user()->first_name . ' memposting pekerjaan baru: ' . $project->title,
            'type' => 'project'
        ]);

        return redirect()->route('dashboard')->with('success', 'Pekerjaan berhasil dipasang dan akan ditinjau.');
    }

    /**
     * Show available active projects for workers.
     */
    public function index(Request $request)
    {
        // Only workers should be able to see available jobs
        $role = session()->get('active_role', 'client');
        if ($role !== 'worker') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya worker yang bisa mencari pekerjaan.');
        }

        if (!Auth::user()->is_active) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda telah dinonaktifkan karena terdeteksi adanya pelanggaran.');
        }

        $workerId = Auth::id();
        $query = Project::whereHas('client', function($q) {
            $q->where('is_active', true);
        })->with(['client', 'tasks' => function($q) use ($workerId) {
            $q->where('worker_id', $workerId);
        }])->whereIn('status', ['active', 'taken']);

        // Filter based on search keyword (title, description, or category)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter based on category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $projects = $query->latest()->get();
        
        // Get all unique categories from visible projects
        $categories = Project::whereHas('client', function($q) {
            $q->where('is_active', true);
        })->whereIn('status', ['active', 'taken'])
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('projects.index', compact('projects', 'categories'));
    }

    /**
     * Show detail of an active project for workers.
     */
    public function jobDetail(Project $project)
    {
        // Only workers should be able to see job details
        $role = session()->get('active_role', 'client');
        if ($role !== 'worker') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya worker yang bisa melihat detail pekerjaan.');
        }

        $project->load('client');
        if (!$project->client || !$project->client->is_active) {
            return redirect()->route('jobs.index')->with('error', 'Pekerjaan ini sudah tidak tersedia.');
        }

        if (!in_array($project->status, ['active', 'taken', 'in_progress', 'completed'])) {
            return redirect()->route('jobs.index')->with('error', 'Pekerjaan ini sudah tidak tersedia.');
        }

        // Cari tahu apakah worker sudah mengambil job ini (untuk mengubah tombol)
        $task = $project->tasks()->where('worker_id', Auth::id())->first();

        return view('projects.job-detail', compact('project', 'task'));
    }

    /**
     * Worker takes an active project.
     */
    public function take(Project $project)
    {
        // Only workers should be able to take jobs
        $role = session()->get('active_role', 'client');
        if ($role !== 'worker') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya worker yang bisa mengambil pekerjaan.');
        }

        // Check if the user is active
        if (!Auth::user()->is_active) {
            return redirect()->back()->with('error', 'Akun Anda sedang dinonaktifkan. Anda tidak dapat mengambil pekerjaan.');
        }

        $project->load('client');
        if (!$project->client || !$project->client->is_active) {
            return redirect()->route('jobs.index')->with('error', 'Pekerjaan ini sudah tidak tersedia.');
        }

        if (!in_array($project->status, ['active', 'taken', 'in_progress', 'completed'])) {
            return redirect()->back()->with('error', 'Pekerjaan ini sudah tidak tersedia.');
        }

        // Cek apakah worker ini sudah pernah mengambil project ini
        if ($project->tasks()->where('worker_id', Auth::id())->exists()) {
            return redirect()->back()->with('error', 'Anda sudah mengambil pekerjaan ini sebelumnya.');
        }

        // Create Task
        $task = Task::create([
            'project_id' => $project->id,
            'worker_id' => Auth::id(),
            'status' => 'in_progress',
        ]);

        // Update Project status
        $project->update(['status' => 'taken']);

        // Log this activity
        ActivityLog::create([
            'description' => 'Worker ' . Auth::user()->first_name . ' mengambil pekerjaan: ' . $project->title,
            'type' => 'task'
        ]);

        return redirect()->route('worker.jobs.show', $project->id)->with('success', 'Pekerjaan berhasil diambil. Silakan hubungi client.');
    }

    /**
     * Client manages their jobs.
     */
    public function manage()
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'client') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya client yang bisa mengelola pekerjaan.');
        }

        $projects = Project::where('client_id', Auth::id())->latest()->get();

        return view('projects.manage', compact('projects'));
    }
    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'client' || $project->client_id !== Auth::id()) {
            return redirect()->route('projects.manage')->with('error', 'Akses ditolak.');
        }

        if (!in_array($project->status, ['pending', 'rejected'])) {
            return redirect()->route('projects.manage')->with('error', 'Pekerjaan yang sudah diproses atau disetujui tidak dapat diedit.');
        }

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'client' || $project->client_id !== Auth::id()) {
            return redirect()->route('projects.manage')->with('error', 'Akses ditolak.');
        }

        if (!in_array($project->status, ['pending', 'rejected'])) {
            return redirect()->route('projects.manage')->with('error', 'Pekerjaan yang sudah diproses atau disetujui tidak dapat diedit.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
        ]);

        // Reset status to pending so admin can re-verify the edited project
        $validated['status'] = 'pending';

        $project->update($validated);

        ActivityLog::create([
            'description' => 'Client ' . Auth::user()->first_name . ' mengedit pekerjaan: ' . $project->title,
            'type' => 'project'
        ]);

        return redirect()->route('projects.manage')->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'client' || $project->client_id !== Auth::id()) {
            return redirect()->route('projects.manage')->with('error', 'Akses ditolak.');
        }

        if (!in_array($project->status, ['pending', 'rejected'])) {
            return redirect()->route('projects.manage')->with('error', 'Pekerjaan yang sudah diproses atau disetujui tidak dapat dihapus.');
        }

        $projectTitle = $project->title;
        $project->delete();

        ActivityLog::create([
            'description' => 'Client ' . Auth::user()->first_name . ' menghapus pekerjaan: ' . $projectTitle,
            'type' => 'project'
        ]);

        return redirect()->route('projects.manage')->with('success', 'Pekerjaan berhasil dihapus.');
    }

    public function show(Project $project)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'client' || $project->client_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        // Cek apakah ada task yang sudah di-upload (status in_review)
        $reviewTask = $project->tasks()->whereIn('status', ['in_review', 'completed'])->where('is_selected', true)->first();
        
        if ($reviewTask) {
            $reviewTask->load(['project', 'worker']);
            
            $uploadedFiles = [];
            if ($reviewTask->upload_path) {
                $uploadedFiles = json_decode($reviewTask->upload_path, true);
            }

            $biayaJasaDasar = $reviewTask->project->budget;
            $biayaLayananPlatform = $biayaJasaDasar * 0.10; // Asumsi 10%
            $totalTagihan = $biayaJasaDasar + $biayaLayananPlatform;

            return view('client.tasks.review', [
                'task' => $reviewTask,
                'uploadedFiles' => $uploadedFiles,
                'biayaJasaDasar' => $biayaJasaDasar,
                'biayaLayananPlatform' => $biayaLayananPlatform,
                'totalTagihan' => $totalTagihan
            ]);
        }

        // Load tasks and the associated workers
        $project->load('tasks.worker');

        return view('projects.show', compact('project'));
    }

    /**
     * Display the applicants for a specific project.
     */
    public function applicants(Project $project)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'client' || $project->client_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        // Load tasks and the associated workers' profiles
        $project->load('tasks.worker');

        return view('projects.applicants', compact('project'));
    }

    /**
     * Client selects a worker for a job, moving the project to in_progress.
     */
    public function selectWorker(Task $task)
    {
        $role = session()->get('active_role', 'client');
        $task->load(['project', 'worker']);
        
        if ($role !== 'client' || $task->project->client_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        // Check if the selected worker is active
        if (!$task->worker || !$task->worker->is_active) {
            return redirect()->back()->with('error', 'Pekerja ini sedang dinonaktifkan.');
        }

        // Update project status to in_progress
        $task->project->update(['status' => 'in_progress']);
        $task->update(['is_selected' => true]);

        ActivityLog::create([
            'description' => 'Client ' . Auth::user()->first_name . ' memilih worker untuk pekerjaan: ' . $task->project->title,
            'type' => 'project'
        ]);

        return redirect()->back()->with('success', 'Berhasil memilih worker. Pekerjaan sekarang dalam status in_progress.');
    }
}
