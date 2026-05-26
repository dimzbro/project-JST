<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    /**
     * Display a listing of the tasks assigned to the worker.
     */
    public function index()
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'worker') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        // Hanya tampilkan pekerjaan di mana worker telah secara resmi dipilih oleh client
        $tasks = Task::with('project')->where('worker_id', Auth::id())->where('is_selected', true)->latest()->get();

        return view('worker.tasks.index', compact('tasks'));
    }

    /**
     * Display the specified task details.
     */
    public function show(Task $task)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'worker' || $task->worker_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        // Load project and client for displaying details
        $task->load('project.client');

        return view('worker.tasks.show', compact('task'));
    }

    /**
     * Display the upload form for the task.
     */
    public function kirimHasil(Task $task)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'worker' || $task->worker_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        return view('worker.tasks.upload', compact('task'));
    }

    /**
     * Handle the file upload for the task.
     */
    public function uploadTugas(Request $request, Task $task)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'worker' || $task->worker_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'file_tugas' => 'required|array|min:1',
            'file_tugas.*' => 'required|file', // remove max size limit
            'notes' => 'nullable|string',
        ], [
            'file_tugas.required' => 'Kolom file pekerjaan harus diisi.',
            'file_tugas.min' => 'Minimal satu file pekerjaan harus diunggah.',
            'file_tugas.*.file' => 'File yang diunggah tidak valid.'
        ]);

        $uploadedFiles = [];
        if ($request->hasFile('file_tugas')) {
            foreach ($request->file('file_tugas') as $file) {
                // Store file and get path
                $path = $file->store('uploads/tugas', 'public');
                $uploadedFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize()
                ];
            }
        }

        if (!empty($uploadedFiles)) {
            $task->upload_path = json_encode($uploadedFiles);
        }
        $task->notes = $request->notes;
        $task->status = 'in_review';
        $task->save();

        return back()->with('success', 'Hasil pekerjaan berhasil diunggah dan sedang menunggu tinjauan Client!');
    }

    /**
     * Display the review page for the client to review the worker's work.
     */
    public function clientReview(Task $task)
    {
        $role = session()->get('active_role', 'client');
        $task->load(['project', 'worker']);
        if ($role !== 'client' || $task->project->client_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        // Parse uploaded files
        $uploadedFiles = [];
        if ($task->upload_path) {
            $uploadedFiles = json_decode($task->upload_path, true);
        }

        // Calculate fees
        $biayaJasaDasar = $task->project->budget;
        $biayaLayananPlatform = $biayaJasaDasar * 0.10; // Assuming 10% platform fee
        $totalTagihan = $biayaJasaDasar + $biayaLayananPlatform;

        return view('client.tasks.review', compact('task', 'uploadedFiles', 'biayaJasaDasar', 'biayaLayananPlatform', 'totalTagihan'));
    }

    /**
     * Client accepts the worker's submitted task.
     */
    public function clientAccept(Task $task)
    {
        $role = session()->get('active_role', 'client');
        $task->load('project');
        if ($role !== 'client' || $task->project->client_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $task->status = 'completed';
        $task->save();

        // Check if all tasks in the project are completed
        $project = $task->project;
        $allDone = $project->tasks()->where('status', '!=', 'completed')->doesntExist();
        if ($allDone) {
            $project->status = 'completed';
            $project->save();
        }

        return back()->with('success', 'Pekerjaan telah diterima dan diselesaikan.');
    }

    /**
     * Client requests revision for the worker's submitted task.
     */
    public function clientRevise(Task $task)
    {
        $role = session()->get('active_role', 'client');
        $task->load('project');
        if ($role !== 'client' || $task->project->client_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $task->status = 'revision_requested';
        $task->save();

        return back()->with('success', 'Revisi telah diminta ke worker.');
    }

    /**
     * Client rates the worker's completed task.
     */
    public function clientRate(Request $request, Task $task)
    {
        $role = session()->get('active_role', 'client');
        $task->load(['project', 'worker']);
        if ($role !== 'client' || $task->project->client_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        if ($task->status !== 'completed') {
            return back()->with('error', 'Pekerjaan belum diselesaikan.');
        }

        if ($task->rating !== null) {
            return back()->with('error', 'Anda sudah memberikan rating untuk pekerjaan ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:10',
        ]);

        $rating = (int) $request->input('rating');
        $task->rating = $rating;
        $task->save();

        // Update the worker's average rating in users table
        $task->worker->addRating($rating);

        return back()->with('success', 'Terima kasih! Rating berhasil diberikan kepada worker.');
    }
}

