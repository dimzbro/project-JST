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
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::get('/jobs', [ProjectController::class, 'index'])->name('jobs.index');
    Route::get('/worker/jobs/{project}', [ProjectController::class, 'jobDetail'])->name('worker.jobs.show');
    Route::post('/jobs/{project}/take', [ProjectController::class, 'take'])->name('jobs.take');

    Route::get('/worker/tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('worker.tasks.index');
    Route::get('/worker/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'show'])->name('worker.tasks.show');

    Route::get('/admin/verifikasi', [App\Http\Controllers\AdminVerificationController::class, 'index'])->name('admin.verification.index');
    Route::get('/admin/verifikasi/{project}', [App\Http\Controllers\AdminVerificationController::class, 'show'])->name('admin.verification.show');
    Route::post('/admin/verifikasi/{project}', [App\Http\Controllers\AdminVerificationController::class, 'verify'])->name('admin.verification.verify');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
