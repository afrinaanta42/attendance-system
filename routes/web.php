<?php

use App\Http\Controllers\{
    ProfileController,
    StudentController,
    TeacherController,
    AttendanceController,
    ClassController,
    DashboardController,
    AuditLogController
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes (if any)
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes (from auth.php)
require __DIR__ . '/auth.php';

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Admin-only routes
    Route::middleware(['role:Admin'])->group(function () {

        // Classes Management
        Route::prefix('classes')->name('classes.')->group(function () {
            Route::get('/', [ClassController::class, 'index'])->name('index');
            Route::get('/create', [ClassController::class, 'create'])->name('create');
            Route::post('/', [ClassController::class, 'store'])->name('store');
            Route::get('/{class}/edit', [ClassController::class, 'edit'])->name('edit');
            Route::put('/{class}', [ClassController::class, 'update'])->name('update');
            Route::delete('/{class}', [ClassController::class, 'destroy'])->name('destroy');
        });

        // Teachers Management
        Route::prefix('teachers')->name('teachers.')->group(function () {
            Route::get('/', [TeacherController::class, 'index'])->name('index');
            Route::get('/create', [TeacherController::class, 'create'])->name('create');
            Route::post('/', [TeacherController::class, 'store'])->name('store');
            Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])->name('edit');
            Route::put('/{teacher}', [TeacherController::class, 'update'])->name('update');
            Route::delete('/{teacher}', [TeacherController::class, 'destroy'])->name('destroy');
        });

        // Audit Logs (if you create this controller)
        Route::prefix('audit-logs')->name('audit-logs.')->group(function () {
            Route::get('/', [AuditLogController::class, 'index'])->name('index');
            Route::get('/{auditLog}', [AuditLogController::class, 'show'])->name('show');
        });

        // System Settings (example - you can create this controller)
        // Route::prefix('settings')->name('settings.')->group(function () {
        //     Route::get('/', [SettingsController::class, 'index'])->name('index');
        //     Route::patch('/general', [SettingsController::class, 'updateGeneral'])->name('update.general');
        //     Route::patch('/attendance', [SettingsController::class, 'updateAttendance'])->name('update.attendance');
        // });
    });

    // Admin & Teacher shared routes
    Route::middleware(['role:Admin,Teacher'])->group(function () {

        // Students Management
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('index');
            Route::get('/create', [StudentController::class, 'create'])->name('create');
            Route::post('/', [StudentController::class, 'store'])->name('store');
            Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
            Route::put('/{student}', [StudentController::class, 'update'])->name('update');
            Route::get('/{student}', [StudentController::class, 'show'])->name('show');
            Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
            Route::get('/{student}/attendance', [StudentController::class, 'attendance'])->name('attendance');
        });

        // Attendance Management
        Route::resource('attendance', AttendanceController::class)->only(['show', 'update', 'destroy']);
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('index');
            Route::post('/store', [AttendanceController::class, 'store'])->name('store');
            Route::post('/fetch-students', [AttendanceController::class, 'fetchStudents'])->name('fetch');
            Route::get('/report', [AttendanceController::class, 'report'])->name('report');
            Route::get('/export', [AttendanceController::class, 'export'])->name('export');
            Route::get('/history', [AttendanceController::class, 'history'])->name('history');
            Route::get('/daily-report', [AttendanceController::class, 'dailyReport'])->name('daily.report');
            Route::get('/monthly-report', [AttendanceController::class, 'monthlyReport'])->name('monthly.report');
        });
    });

    // Teacher & Admin attendance routes (redundant but explicit)
    Route::middleware(['role:Teacher,Admin'])->group(function () {
        // These routes are already defined above, but kept for clarity
    });


    // Student-only routes
    Route::middleware(['role:Student'])->group(function () {
        Route::prefix('my')->name('my.')->group(function () {
            // Student Attendance
            Route::get('/attendance', [AttendanceController::class, 'myAttendance'])->name('attendance');
            // Student Profile
            Route::get('/profile', [ProfileController::class, 'studentProfile'])->name('profile');
            // Student Schedule
            Route::get('/schedule', function () {
                return view('students.my.schedule');
            })->name('schedule');
            // Student Grades
            Route::get('/grades', function () {
                return view('students.my.grades');
            })->name('grades');
        });
    });


    // Common routes for all authenticated users
    Route::prefix('common')->name('common.')->group(function () {
        Route::get('/calendar', function () {
            return view('common.calendar');
        })->name('calendar');

        Route::get('/notifications', function () {
            return view('common.notifications');
        })->name('notifications');

        Route::get('/help', function () {
            return view('common.help');
        })->name('help');
    });

    // API-like routes for AJAX calls
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/attendance-summary', [AttendanceController::class, 'getSummary'])->name('attendance.summary');
        Route::get('/class-stats/{class}', [ClassController::class, 'getStats'])->name('class.stats');
        Route::get('/student-attendance/{student}', [StudentController::class, 'getAttendanceStats'])->name('student.attendance.stats');
    });
});

// Fallback route (must be at the end)
Route::fallback(function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
