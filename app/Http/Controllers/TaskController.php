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
}
