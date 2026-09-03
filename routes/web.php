<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Projects — create/edit/delete (admin + analis)
    Route::middleware('role:admin,analis')->group(function () {
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

        // Weekly Reports Management (Create/Update/Delete)
        Route::get('/projects/{project}/weekly-reports/create', [App\Http\Controllers\WeeklyReportController::class, 'create'])->name('weekly-reports.create');
        Route::post('/projects/{project}/weekly-reports', [App\Http\Controllers\WeeklyReportController::class, 'store'])->name('weekly-reports.store');
        Route::get('/weekly-reports/{weeklyReport}/edit', [App\Http\Controllers\WeeklyReportController::class, 'edit'])->name('weekly-reports.edit');
        Route::put('/weekly-reports/{weeklyReport}', [App\Http\Controllers\WeeklyReportController::class, 'update'])->name('weekly-reports.update');
        Route::post('/weekly-reports/{weeklyReport}/visuals', [App\Http\Controllers\WeeklyReportController::class, 'storeVisuals'])->name('weekly-reports.visuals.store');
        Route::post('/weekly-reports/{weeklyReport}/cover-layout', [App\Http\Controllers\WeeklyReportController::class, 'saveCoverLayout'])->name('weekly-reports.cover-layout.save');
        Route::delete('/weekly-reports/{weeklyReport}', [App\Http\Controllers\WeeklyReportController::class, 'destroy'])->name('weekly-reports.destroy');

        // BoQ (Work Items) Management
        Route::post('/projects/{project}/work-items', [App\Http\Controllers\WorkItemController::class, 'store'])->name('work-items.store');
        Route::put('/work-items/{workItem}', [App\Http\Controllers\WorkItemController::class, 'update'])->name('work-items.update');
        Route::delete('/work-items/{workItem}', [App\Http\Controllers\WorkItemController::class, 'destroy'])->name('work-items.destroy');
    });

    // Projects — view (all roles)
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    
    // Weekly Reports - list and dashboard (all roles)
    Route::get('/weekly-reports', [App\Http\Controllers\WeeklyReportController::class, 'projects'])->name('weekly-reports.projects');
    Route::get('/weekly-reports/projects/{project}', [App\Http\Controllers\WeeklyReportController::class, 'projectDashboard'])->name('weekly-reports.project-dashboard');
    
    // Weekly Reports - view and download PDF (all roles)
    Route::get('/weekly-reports/{weeklyReport}', [App\Http\Controllers\WeeklyReportController::class, 'show'])->name('weekly-reports.show');
    Route::get('/weekly-reports/{weeklyReport}/download-pdf', [App\Http\Controllers\WeeklyReportController::class, 'downloadPdf'])->name('weekly-reports.download-pdf');
        
    // Gantt Schedule Toggle & Export
    Route::post('/projects/{project}/toggle-gantt', [App\Http\Controllers\ProjectController::class, 'toggleGantt'])->name('projects.toggle-gantt');
    Route::get('/projects/{project}/gantt/pdf', [App\Http\Controllers\WeeklyReportController::class, 'exportGanttPdf'])->name('projects.gantt.pdf');

    // User management (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', App\Http\Controllers\UserController::class)->except(['show']);
        
        Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    });
});

require __DIR__.'/auth.php';
