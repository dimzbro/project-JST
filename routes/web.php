<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/manage', [ProjectController::class, 'manage'])->name('projects.manage');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/client/projects/{project}/applicants', [ProjectController::class, 'applicants'])->name('client.projects.applicants');
    Route::post('/client/tasks/{task}/select', [ProjectController::class, 'selectWorker'])->name('client.tasks.select');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::get('/jobs', [ProjectController::class, 'index'])->name('jobs.index');
    Route::get('/worker/jobs/{project}', [ProjectController::class, 'jobDetail'])->name('worker.jobs.show');
    Route::post('/jobs/{project}/take', [ProjectController::class, 'take'])->name('jobs.take');

    Route::get('/worker/tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('worker.tasks.index');
    Route::get('/worker/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'show'])->name('worker.tasks.show');
    Route::get('/worker/tasks/{task}/upload', [\App\Http\Controllers\TaskController::class, 'kirimHasil'])->name('worker.tasks.upload');
    Route::post('/worker/tasks/{task}/upload', [\App\Http\Controllers\TaskController::class, 'uploadTugas'])->name('worker.tasks.upload.store');

    // Internal Chat Routes
    Route::get('/client/chat/{task}/initiate', [\App\Http\Controllers\ChatController::class, 'clientInitiate'])->name('client.chat.initiate');
    Route::get('/worker/chat/{task}', [\App\Http\Controllers\ChatController::class, 'workerShow'])->name('worker.chat.show');

    Route::get('/admin/verifikasi', [App\Http\Controllers\AdminVerificationController::class, 'index'])->name('admin.verification.index');
    Route::get('/admin/verifikasi/{project}', [App\Http\Controllers\AdminVerificationController::class, 'show'])->name('admin.verification.show');
    Route::post('/admin/verifikasi/{project}', [App\Http\Controllers\AdminVerificationController::class, 'verify'])->name('admin.verification.verify');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
