<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $request->session()->get('active_role', 'client');

        $activeJobsCount = 0;
        $postedJobsCount = 0;
        $completedJobsCount = 0;

        // Admin stats
        $totalUsers = 0;
        $adminActiveJobs = 0;
        $adminCompletedTasks = 0;
        $totalTransactions = 0;
        $activityLogs = collect();

        if ($role === 'client') {
            $postedJobsCount = Project::where('client_id', $user->id)->count();
            // Client's completed jobs are projects they posted that are marked as completed
            $completedJobsCount = Project::where('client_id', $user->id)->where('status', 'completed')->count();
        } else if ($role === 'worker') {
            // Worker's active jobs are tasks they are currently working on
            $activeJobsCount = Task::where('worker_id', $user->id)->where('status', 'in_progress')->count();
            $completedJobsCount = Task::where('worker_id', $user->id)->where('status', 'completed')->count();
        } else if ($role === 'admin') {
            $totalUsers = \App\Models\User::count();
            $adminActiveJobs = Project::where('status', 'active')->count();
            $adminCompletedTasks = Task::where('status', 'completed')->count();
            $totalTransactions = Project::where('status', 'completed')->sum('budget');
            $activityLogs = \App\Models\ActivityLog::latest()->take(10)->get();
        }

        return view('dashboard', [
            'role' => $role,
            'user' => $user,
            'activeJobsCount' => $activeJobsCount,
            'postedJobsCount' => $postedJobsCount,
            'completedJobsCount' => $completedJobsCount,
            'totalUsers' => $totalUsers,
            'adminActiveJobs' => $adminActiveJobs,
            'adminCompletedTasks' => $adminCompletedTasks,
            'totalTransactions' => $totalTransactions,
            'activityLogs' => $activityLogs,
        ]);
    }
}
