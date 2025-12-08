<aside class="hidden md:flex md:flex-col md:w-64 bg-white border-r border-gray-200">
    <!-- Logo -->
    <div class="flex items-center h-16 px-4 border-b border-gray-200">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
            <div
                class="w-10 h-10 bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                <i class="fas fa-chalkboard-teacher text-white text-lg"></i>
            </div>
            <span class="text-xl font-bold text-gray-900">Attendance</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 overflow-y-auto">
        <div class="space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i
                    class="fas fa-home mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                Dashboard
            </a>

            <!-- Admin Navigation -->
            @auth
                @if (auth()->user()->isAdmin())
                    <div class="pt-4">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Admin</p>

                        <a href="{{ route('classes.index') }}"
                            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                                  {{ request()->routeIs('classes.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i
                                class="fas fa-chalkboard mr-3 {{ request()->routeIs('classes.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                            Classes
                        </a>

                        <a href="{{ route('teachers.index') }}"
                            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                                  {{ request()->routeIs('teachers.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i
                                class="fas fa-user-tie mr-3 {{ request()->routeIs('teachers.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                            Teachers
                        </a>

                        <a href="{{ route('students.index') }}"
                            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                                  {{ request()->routeIs('students.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i
                                class="fas fa-user-graduate mr-3 {{ request()->routeIs('students.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                            Students
                        </a>
                    </div>
                @endif

                <!-- Teacher Navigation -->
                @if (auth()->user()->isTeacher() || auth()->user()->isAdmin())
                    <div class="pt-4">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Attendance</p>

                        <a href="{{ route('attendance.index') }}"
                            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                                  {{ request()->routeIs('attendance.index') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i
                                class="fas fa-calendar-check mr-3 {{ request()->routeIs('attendance.index') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                            Mark Attendance
                        </a>

                        <a href="{{ route('attendance.report') }}"
                            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                                  {{ request()->routeIs('attendance.report') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i
                                class="fas fa-chart-bar mr-3 {{ request()->routeIs('attendance.report') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                            Reports
                        </a>
                    </div>
                @endif

                <!-- Student Navigation -->
                @if (auth()->user()->isStudent())
                    <div class="pt-4">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Student</p>

                        <a href="{{ route('attendance.my') }}"
                            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                                  {{ request()->routeIs('attendance.my') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i
                                class="fas fa-user-clock mr-3 {{ request()->routeIs('attendance.my') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                            My Attendance
                        </a>
                    </div>
                @endif

                <!-- Profile -->
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Account</p>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                              {{ request()->routeIs('profile.edit') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i
                            class="fas fa-user-circle mr-3 {{ request()->routeIs('profile.edit') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        Profile
                    </a>
                </div>
            @endauth
        </div>
    </nav>

    <!-- User Info -->
    @auth
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-700 flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">
                        @if (auth()->user()->isAdmin())
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                Admin
                            </span>
                        @elseif(auth()->user()->isTeacher())
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Teacher
                            </span>
                        @elseif(auth()->user()->isStudent())
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                Student
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endauth
</aside>

<!-- Mobile sidebar overlay -->
<div x-data="{ sidebarOpen: false }">
    <!-- Mobile menu button -->
    <div class="md:hidden">
        <button @click="sidebarOpen = true"
            class="ml-4 mt-4 p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>

    <!-- Mobile sidebar -->
    <div x-show="sidebarOpen" @click.away="sidebarOpen = false" class="fixed inset-0 z-40 md:hidden">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button @click="sidebarOpen = false"
                    class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <i class="fas fa-times text-white text-lg"></i>
                </button>
            </div>

            <!-- Mobile sidebar content (same as desktop but compact) -->
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex-shrink-0 flex items-center px-4">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-white text-lg"></i>
                    </div>
                    <span class="ml-3 text-xl font-bold text-gray-900">Attendance</span>
                </div>

                <nav class="mt-5 px-2 space-y-1">
                    <!-- Mobile navigation items (same structure as desktop) -->
                    <a href="{{ route('dashboard') }}" @click="sidebarOpen = false"
                        class="flex items-center px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i
                            class="fas fa-home mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        Dashboard
                    </a>

                    <!-- Add other mobile navigation items similarly -->
                </nav>
            </div>

            <!-- Mobile user info -->
            @auth
                <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-blue-700 flex items-center justify-center">
                            <span class="text-white text-xs font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">
                                @if (auth()->user()->isAdmin())
                                    Admin
                                @elseif(auth()->user()->isTeacher())
                                    Teacher
                                @elseif(auth()->user()->isStudent())
                                    Student
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
