<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Show the tasks taken by the current worker.
     */
    public function myTasks()
    {
        // Only workers should be able to see their tasks
        $role = session()->get('active_role', 'client');
        if ($role !== 'worker') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya worker yang memiliki daftar tugas.');
        }

        $tasks = Task::with('project')->where('worker_id', Auth::id())->latest()->get();

        return view('tasks.my_tasks', compact('tasks'));
    }
}
