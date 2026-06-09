<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    /**
     * Display a listing of system users.
     */
    public function index(Request $request)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $query = User::query();

        // Search filter (first name, last name, or email)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Toggle active status of a user.
     */
    public function toggleStatus(Request $request, User $user)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        // Prevent admin from deactivating their own account
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        if (!$user->is_active) {
            // Find all tasks of this worker that are not completed
            $tasks = \App\Models\Task::where('worker_id', $user->id)
                ->where('status', '!=', 'completed')
                ->get();

            foreach ($tasks as $task) {
                $project = $task->project;
                $task->delete();

                if ($project) {
                    $hasSelectedWorker = $project->tasks()->where('is_selected', true)->exists();
                    $hasApplicants = $project->tasks()->exists();

                    if (!$hasSelectedWorker && $project->status === 'in_progress') {
                        $project->status = 'active';
                        $project->save();
                    } elseif (!$hasApplicants && $project->status === 'taken') {
                        $project->status = 'active';
                        $project->save();
                    }
                }
            }
        }

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        // Log this activity to activity log
        ActivityLog::create([
            'description' => 'Admin ' . Auth::user()->first_name . ' telah ' . $statusText . ' akun pengguna: ' . $user->first_name . ' ' . $user->last_name,
            'type' => 'user'
        ]);

        return redirect()->back()->with('success', 'Akun ' . $user->first_name . ' ' . $user->last_name . ' berhasil ' . $statusText . '.');
    }
}
