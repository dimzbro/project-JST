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

        $workerId = Auth::id();
        $query = Project::with(['client', 'tasks' => function($q) use ($workerId) {
            $q->where('worker_id', $workerId);
        }])->whereIn('status', ['active', 'taken', 'in_progress', 'completed']);

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
        $categories = Project::whereIn('status', ['active', 'taken', 'in_progress', 'completed'])
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

        if (!in_array($project->status, ['active', 'taken', 'in_progress', 'completed'])) {
            return redirect()->route('jobs.index')->with('error', 'Pekerjaan ini sudah tidak tersedia.');
        }

        // Jika worker sudah pernah mengambil job ini, arahkan ke detail tugasnya
        if ($project->tasks()->where('worker_id', Auth::id())->exists()) {
            $taskId = $project->tasks()->where('worker_id', Auth::id())->first()->id;
            return redirect()->route('worker.tasks.show', $taskId);
        }

        // Load client details
        $project->load('client');

        return view('projects.job-detail', compact('project'));
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

        return redirect()->route('worker.tasks.show', $task->id)->with('success', 'Pekerjaan berhasil diambil. Silakan kerjakan tugas ini.');
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

    /**
     * Display the specified project and its applicants (tasks).
     */
    public function show(Project $project)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'client' || $project->client_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        // Load tasks and the associated workers
        $project->load('tasks.worker');

        return view('projects.show', compact('project'));
    }
}
