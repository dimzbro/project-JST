<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminVerificationController extends Controller
{
    public function index()
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $pendingCount = Project::where('status', 'pending')->count();
        $verifiedCount = Project::whereIn('status', ['active', 'taken', 'completed'])->count();
        $rejectedCount = Project::where('status', 'rejected')->count();

        $pendingProjects = Project::with('client')->where('status', 'pending')->latest()->get();

        return view('admin.verification.index', compact('pendingCount', 'verifiedCount', 'rejectedCount', 'pendingProjects'));
    }

    public function show(Project $project)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        return view('admin.verification.show', compact('project'));
    }

    public function verify(Request $request, Project $project)
    {
        $role = session()->get('active_role', 'client');
        if ($role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $action = $request->input('action');
        if ($action === 'approve') {
            $project->update(['status' => 'active']);
            ActivityLog::create([
                'description' => 'Admin ' . Auth::user()->first_name . ' memverifikasi pekerjaan: ' . $project->title,
                'type' => 'project'
            ]);
            return redirect()->route('admin.verification.index')->with('success', 'Pekerjaan berhasil diverifikasi.');
        } elseif ($action === 'reject') {
            $project->update(['status' => 'rejected']);
            ActivityLog::create([
                'description' => 'Admin ' . Auth::user()->first_name . ' menolak pekerjaan: ' . $project->title,
                'type' => 'project'
            ]);
            return redirect()->route('admin.verification.index')->with('success', 'Pekerjaan ditolak.');
        }

        return redirect()->back();
    }
}
