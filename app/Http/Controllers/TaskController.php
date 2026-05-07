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

        $tasks = Task::with('project')->where('worker_id', Auth::id())->latest()->get();

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
}
