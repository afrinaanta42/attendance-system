<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:Student,Teacher'],
            'roll_number' => ['nullable', 'required_if:role,Student', 'unique:students,roll_number'],
            'gender' => ['nullable', 'required_if:role,Student', 'in:Male,Female,Other'],
            'subject' => ['nullable', 'string', 'max:255'],
            'terms' => ['required', 'accepted'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign role
        $role = Role::where('name', $request->role)->first();
        if ($role) {
            $user->roles()->attach($role);
        }

        // Create profile based on role
        if ($request->role === 'Student') {
            Student::create([
                'user_id' => $user->id,
                'roll_number' => $request->roll_number,
                'gender' => $request->gender,
            ]);
        } elseif ($request->role === 'Teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'subject' => $request->subject,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
