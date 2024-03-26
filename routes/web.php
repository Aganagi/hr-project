<?php

use App\Livewire\Department;
use App\Livewire\Employee;
use App\Livewire\LeaveAndAbsence;
use App\Livewire\Position;
use App\Livewire\Project;
use App\Livewire\WorkSchedule;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Volt::route('department', Department::class)->name('department');
    Volt::route('position', Position::class)->name('position');
    Volt::route('employee', Employee::class)->name('employee');
    Volt::route('workSchedule', WorkSchedule::class)->name('workSchedule');
    Volt::route('project', Project::class)->name('project');
    Volt::route('leaveAndAbsence', LeaveAndAbsence::class)->name('leaveAndAbsence');
    Route::view('profile', 'profile')->name('profile');
});

require __DIR__ . '/auth.php';
