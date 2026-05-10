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
        $task->status = 'completed';
        $task->save();

        // Update status project jika semua task completed
        $project = $task->project;
        $allDone = $project->tasks()->where('status', 'in_progress')->doesntExist();
        if ($allDone) {
            $project->status = 'completed';
            $project->save();
        }

        return back()->with('success', 'Pekerjaan Terkirim!');
    }
}
