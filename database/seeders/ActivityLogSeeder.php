<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;

class ActivityLogSeeder extends Seeder
{
    public function run()
    {
        ActivityLog::create([
            'description' => 'User <span class="font-bold">Budi Santoso</span> mendaftar sebagai Worker.',
            'type' => 'user',
            'created_at' => now()->subMinutes(2),
        ]);

        ActivityLog::create([
            'description' => 'Proyek <span class="font-bold">Desain Logo</span> telah diselesaikan.',
            'type' => 'task',
            'created_at' => now()->subMinutes(15),
        ]);

        ActivityLog::create([
            'description' => 'Client <span class="font-bold">PT Maju</span> memposting lowongan baru.',
            'type' => 'project',
            'created_at' => now()->subHours(1),
        ]);

        ActivityLog::create([
            'description' => 'Transaksi masuk sebesar Rp 2.500.000',
            'type' => 'transaction',
            'created_at' => now()->subHours(3),
        ]);
    }
}
