<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoterProfileController;
use App\Http\Controllers\RevokePermissionFromRoleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoterController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;







Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    });
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissionController::class);
    Route::delete('/roles/{role}/permissions/{permission}', RevokePermissionFromRoleController::class)->name('roles.permission.destroy');

    Route::resource('/users', UserController::class);

    Route::get('/votersprofile/position/{position}/{id?}/{downline?}', [VoterProfileController::class, 'showByPosition'])->name('votersprofile.showposition');

    Route::get('/votersprofile/view/{id}', [VoterProfileController::class, 'viewProfile'])->name('votersprofile.viewprofile');
    // Route::get('/votersprofile/edit/{id}', [VoterProfileController::class, 'edit'])->name('votersprofile.edit');
    // Route::get('/votersprofile/{id}/members', [VoterProfileController::class, 'showByPosition'])->name('votersprofile.showmembers');
    Route::resource('/votersprofile', VoterProfileController::class);
});

Route::get('/initvoters/{file}', [VoterController::class, 'initVoters']);

require __DIR__ . '/auth.php';
