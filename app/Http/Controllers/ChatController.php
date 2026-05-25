<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Client initiates chat with worker.
     * Sets session state and redirects to chat UI.
     */
    public function clientInitiate(Task $task)
    {
        // Ensure user is the client of the project
        if ($task->project->client_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke percakapan ini.');
        }

        // Simulate creating a chat room globally using Cache instead of session (survives logout)
        \Illuminate\Support\Facades\Cache::put('chat_created_' . $task->id, true, now()->addDays(1));

        // Get the opposing user details (for client, it's the worker)
        $targetUser = $task->worker;
        $isClient = true;

        return view('chat.room', compact('task', 'targetUser', 'isClient'));
    }

    /**
     * Worker attempts to open chat with client.
     * Validates cache state first.
     */
    public function workerShow(Task $task)
    {
        // Ensure user is the worker assigned to the task
        if ($task->worker_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke percakapan ini.');
        }

        // If chat hasn't been created, mark it as created so they can chat
        if (!\Illuminate\Support\Facades\Cache::has('chat_created_' . $task->id)) {
            \Illuminate\Support\Facades\Cache::put('chat_created_' . $task->id, true, now()->addDays(1));
        }

        // Get the opposing user details (for worker, it's the client)
        $targetUser = $task->project->client;
        $isClient = false;

        return view('chat.room', compact('task', 'targetUser', 'isClient'));
    }
}
