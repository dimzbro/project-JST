<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        if (!Auth::user()->is_active) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda telah dinonaktifkan karena terdeteksi adanya pelanggaran.');
        }

        $user = Auth::user();

        // Query semua task di mana user ini berperan sebagai worker dan sudah diberi rating
        $receivedRatings = \App\Models\Task::with(['project.client'])
            ->where('worker_id', $user->id)
            ->whereNotNull('rating')
            ->latest()
            ->get();

        return view('profile', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        if (!Auth::user()->is_active) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda telah dinonaktifkan karena terdeteksi adanya pelanggaran.');
        }

        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'about_me' => 'nullable|string',
            'skills' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone_number = $request->phone_number;
        $user->about_me = $request->about_me;
        $user->skills = $request->skills;

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->save();

        return back()->with('status', 'profile-updated');
    }
}
