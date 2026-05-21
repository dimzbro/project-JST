<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$t = App\Models\Task::latest()->first();
echo json_encode(['status' => $t ? $t->status : null, 'upload' => $t ? $t->upload_path : null]);
