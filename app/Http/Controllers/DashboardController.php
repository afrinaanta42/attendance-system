<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // dd($user, 'admin');
            return view('dashboard');
        } elseif ($user->isTeacher()) {
            // dd($user, 'teacher');
            return view('dashboard');
        } elseif ($user->isStudent()) {
            // dd($user, 'student');
            return view('dashboard');
        }

        return view('dashboard');
    }
}
