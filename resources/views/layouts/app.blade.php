<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Attendance System')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-gray-50 font-sans antialiased">

    <div class="min-h-full flex">
        <!-- Sidebar -->
        <div
            class="fixed inset-y-0 left-0 w-64 bg-indigo-800 shadow-lg transform transition-transform duration-200 ease-in-out z-30">
            <div class="h-full flex flex-col">
                <div class="flex items-center justify-center h-16 bg-indigo-900">
                    <h1 class="text-white text-xl font-bold">Attendance Pro</h1>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-indigo-700 {{ request()->routeIs('dashboard') ? 'bg-indigo-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        Dashboard
                    </a>

                    @if (auth()->user()->isAdmin())
                        <x-sidebar-link href="{{ route('classes.index') }}" :active="request()->routeIs('classes.*')">
                            Classes
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teachers.index') }}" :active="request()->routeIs('teachers.*')">
                            Teachers
                        </x-sidebar-link>
                    @endif

                    @if (auth()->user()->isAdmin() || auth()->user()->isTeacher())
                        <x-sidebar-link href="{{ route('students.index') }}" :active="request()->routeIs('students.*')">
                            Students
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('attendance.index') }}" :active="request()->routeIs('attendance.*')">
                            Mark Attendance
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('attendance.report') }}" :active="request()->routeIs('attendance.report')">
                            Attendance Report
                        </x-sidebar-link>
                    @endif

                    @if (auth()->user()->isStudent())
                        <x-sidebar-link href="{{ route('attendance.my') }}" :active="request()->routeIs('attendance.my')">
                            My Attendance
                        </x-sidebar-link>
                    @endif
                </nav>

                <div class="p-4 border-t border-indigo-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center text-left px-4 py-3 text-white rounded-lg hover:bg-indigo-700">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4 flex items-center justify-between flex">
                    <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            {{ auth()->user()->name }}
                            <span
                                class="text-indigo-600 font-medium">({{ auth()->user()->roles->pluck('name')->implode(', ') }})</span>
                        </span>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff"
                            alt="Avatar" class="w-10 h-10 rounded-full">
                    </div>
                </div>
            </header>

            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
