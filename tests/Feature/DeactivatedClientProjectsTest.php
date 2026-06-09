<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeactivatedClientProjectsTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_from_deactivated_clients_are_hidden_and_blocked(): void
    {
        // 1. Create worker
        $worker = User::factory()->create([
            'is_active' => true,
        ]);

        // 2. Create active client and project
        $activeClient = User::factory()->create([
            'is_active' => true,
        ]);
        $activeProject = Project::create([
            'client_id' => $activeClient->id,
            'title' => 'Project Active Client',
            'description' => 'Description here',
            'status' => 'active',
            'budget' => 500000,
        ]);

        // 3. Create deactivated client and project
        $deactivatedClient = User::factory()->create([
            'is_active' => false,
        ]);
        $deactivatedProject = Project::create([
            'client_id' => $deactivatedClient->id,
            'title' => 'Project Deactivated Client',
            'description' => 'Description here',
            'status' => 'active',
            'budget' => 300000,
        ]);

        // 4. Authenticate as worker
        $this->actingAs($worker)->withSession(['active_role' => 'worker']);

        // 5. Test index: deactivated client project should be hidden
        $response = $this->get('/jobs');
        $response->assertStatus(200);
        $response->assertSee('Project Active Client');
        $response->assertDontSee('Project Deactivated Client');

        // 6. Test detail: detail page for deactivated client project should be blocked (redirected)
        $response = $this->get('/worker/jobs/' . $deactivatedProject->id);
        $response->assertRedirect('/jobs');
        $response->assertSessionHas('error', 'Pekerjaan ini sudah tidak tersedia.');

        // 7. Test take: worker cannot take deactivated client project
        $response = $this->post('/jobs/' . $deactivatedProject->id . '/take');
        $response->assertRedirect('/jobs');
        $response->assertSessionHas('error', 'Pekerjaan ini sudah tidak tersedia.');
    }

    public function test_tasks_for_deactivated_clients_warn_and_block_uploads(): void
    {
        $worker = User::factory()->create([
            'is_active' => true,
        ]);

        $deactivatedClient = User::factory()->create([
            'is_active' => false,
        ]);

        $project = Project::create([
            'client_id' => $deactivatedClient->id,
            'title' => 'Project Assigned',
            'description' => 'Description here',
            'status' => 'taken',
            'budget' => 300000,
        ]);

        $task = \App\Models\Task::create([
            'project_id' => $project->id,
            'worker_id' => $worker->id,
            'status' => 'in_progress',
            'is_selected' => true,
        ]);

        $this->actingAs($worker)->withSession(['active_role' => 'worker']);

        $response = $this->get('/worker/tasks');
        $response->assertStatus(200);
        $response->assertSee('Client Nonaktif');

        $response = $this->get('/worker/tasks/' . $task->id);
        $response->assertStatus(200);
        $response->assertSee('Perhatian: Akun Client pekerjaan ini sedang dinonaktifkan. Anda tidak dapat melakukan interaksi atau mengirim hasil pekerjaan saat ini.');
        $response->assertSee('Unggah Hasil (Client Nonaktif)');

        $response = $this->get('/worker/tasks/' . $task->id . '/upload');
        $response->assertRedirect('/worker/tasks/' . $task->id);
        $response->assertSessionHas('error', 'Klien pekerjaan ini sedang dinonaktifkan. Anda tidak dapat mengunggah hasil pekerjaan saat ini.');

        $response = $this->post('/worker/tasks/' . $task->id . '/upload', [
            'file_tugas' => [],
        ]);
        $response->assertRedirect('/worker/tasks/' . $task->id);
        $response->assertSessionHas('error', 'Klien pekerjaan ini sedang dinonaktifkan. Anda tidak dapat mengunggah hasil pekerjaan saat ini.');
    }

    public function test_client_cannot_select_suspended_worker(): void
    {
        $client = User::factory()->create([
            'is_active' => true,
        ]);

        $suspendedWorker = User::factory()->create([
            'is_active' => false,
        ]);

        $project = Project::create([
            'client_id' => $client->id,
            'title' => 'Project Open',
            'description' => 'Description here',
            'status' => 'active',
            'budget' => 300000,
        ]);

        $task = \App\Models\Task::create([
            'project_id' => $project->id,
            'worker_id' => $suspendedWorker->id,
            'status' => 'in_progress',
        ]);

        $this->actingAs($client)->withSession(['active_role' => 'client']);

        $response = $this->get('/client/projects/' . $project->id . '/applicants');
        $response->assertStatus(200);
        $response->assertSee('Nonaktif');
        $response->assertSee('Worker Nonaktif');

        $response = $this->post('/client/tasks/' . $task->id . '/select');
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Pekerja ini sedang dinonaktifkan.');
    }

    public function test_project_resets_and_task_deleted_when_worker_suspended(): void
    {
        $admin = User::factory()->create([
            'is_active' => true,
            'is_admin' => true,
        ]);

        $client = User::factory()->create([
            'is_active' => true,
        ]);

        $worker = User::factory()->create([
            'is_active' => true,
        ]);

        $project = Project::create([
            'client_id' => $client->id,
            'title' => 'Project Assignment',
            'description' => 'Description here',
            'status' => 'in_progress',
            'budget' => 300000,
        ]);

        $task = \App\Models\Task::create([
            'project_id' => $project->id,
            'worker_id' => $worker->id,
            'status' => 'in_progress',
            'is_selected' => true,
        ]);

        $this->actingAs($admin)->withSession(['active_role' => 'admin']);

        $response = $this->post('/admin/pengguna/' . $worker->id . '/toggle');
        $response->assertRedirect();

        $worker->refresh();
        $this->assertFalse($worker->is_active);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);

        $project->refresh();
        $this->assertEquals('active', $project->status);
    }
}
