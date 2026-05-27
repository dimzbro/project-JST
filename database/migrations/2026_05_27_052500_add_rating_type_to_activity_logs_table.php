<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds 'rating' to the type ENUM column on activity_logs table.
     */
    public function up(): void
    {
        // ALTER TABLE directly because Laravel Blueprint doesn't support modifying ENUM easily
        DB::statement("ALTER TABLE activity_logs MODIFY COLUMN `type` ENUM('user','project','task','transaction','rating') DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE activity_logs MODIFY COLUMN `type` ENUM('user','project','task','transaction') DEFAULT 'user'");
    }
};
