<?php

use App\Http\Controllers\{
    ProfileController,
    StudentController,
    TeacherController,
    AttendanceController,
    ClassController
};
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('classes', ClassController::class);
    Route::resource('teachers', TeacherController::class)->except(['show']);
});

Route::middleware(['auth', 'role:Admin,Teacher'])->group(function () {
    Route::resource('students', StudentController::class);
});

Route::middleware(['auth', 'role:Teacher,Admin'])->group(function () {
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('attendance/fetch-students', [AttendanceController::class, 'fetchStudents'])->name('attendance.fetch');
    Route::get('attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
    Route::get('attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');
});

Route::middleware(['auth', 'role:Student'])->group(function () {
    Route::get('my-attendance', [AttendanceController::class, 'myAttendance'])->name('attendance.my');
});

require __DIR__ . '/auth.php';
