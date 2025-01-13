<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChristianCommunityController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoterProfileController;
use App\Http\Controllers\RevokePermissionFromRoleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoterController;
use App\Models\Voter;
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



Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissionController::class);
    Route::delete('/roles/{role}/permissions/{permission}', RevokePermissionFromRoleController::class)->name('roles.permission.destroy');

    Route::resource('/users', UserController::class);

    /** BEGIN KIEL's VOTER'S PROFILE ROUTE/RESOURCE */
    Route::get('/votersprofile/position/{position}/{action?}/{id?}', [VoterProfileController::class, 'showByPosition'])->name('votersprofile.showposition');
    Route::get('/votersprofile/view/{id}', [VoterProfileController::class, 'viewProfile'])->name('votersprofile.viewprofile');
    Route::post('/votersprofile/add_downline', [VoterProfileController::class, 'addDownline'])->name('votersprofile.adddownline');
    Route::get('/votersprofile/downline/{id}', [VoterProfileController::class, 'showDownline'])->name('votersprofile.showdownline');

    Route::resource('/votersprofile', VoterProfileController::class);
    Route::delete('/votersprofile', [VoterProfileController::class, 'bulkDelete'])->name('votersprofile.bulkdelete');
    /** END */

    /** BEGIN CHRISTIAN COMMUNITY PROFILE ROUTE/RESOURCE */
    Route::get('/christiancommunity/position/{position}/{action?}/{id?}', [ChristianCommunityController::class, 'showByPosition'])->name('christiancommunity.showposition');
    Route::get('/christiancommunity/view/{id}', [ChristianCommunityController::class, 'viewProfile'])->name('christiancommunity.viewprofile');
    Route::post('/christiancommunity/add_downline', [ChristianCommunityController::class, 'addDownline'])->name('christiancommunity.adddownline');
    Route::get('/christiancommunity/downline/{id}', [ChristianCommunityController::class, 'showDownline'])->name('christiancommunity.showdownline');

    Route::resource('/christiancommunity', ChristianCommunityController::class);
    Route::delete('/christiancommunity', [ChristianCommunityController::class, 'bulkDelete'])->name('christiancommunity.bulkdelete');
    /** END */

    /** BEGIN VOTER'S LIST ROUTE/RESOURCE */
    Route::get('/voterslist', [VoterController::class, 'findVoter'])->name('voterslist.index');
    Route::get('/voterslist.api', [VoterController::class, 'findVoterApi'])->name('voterslist.api');
});

Route::get('/initvoters/{file}', [VoterController::class, 'initVoters']);

require __DIR__ . '/auth.php';
