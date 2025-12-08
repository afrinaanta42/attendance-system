<header class="bg-white border-b border-gray-200">
    <div class="flex items-center justify-between h-16 px-4">
        <!-- Left side: Search and breadcrumb -->
        <div class="flex items-center space-x-4">
            <!-- Page Title -->
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    @hasSection('title')
                        @yield('title')
                    @else
                        {{ $__env->yieldContent('header') ?? 'Dashboard' }}
                    @endif
                </h1>

                <!-- Breadcrumb -->
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <i class="fas fa-home mr-2 text-gray-500"></i>
                                Home
                            </a>
                        </li>

                        @hasSection('breadcrumb')
                            @yield('breadcrumb')
                        @endif
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Right side: User menu and notifications -->
        <div class="flex items-center space-x-3">
            <!-- Notifications -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full relative">
                    <i class="fas fa-bell text-lg"></i>
                    @if (false)
                        <!-- Add notification count logic here -->
                        <span
                            class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    @endif
                </button>

                <!-- Dropdown menu -->
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-900">Notifications</h3>
                    </div>
                    <div class="p-4">
                        <p class="text-sm text-gray-500 text-center">No new notifications</p>
                    </div>
                </div>
            </div>

            <!-- User menu -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100">
                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-blue-700 flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </span>
                        </div>
                        <div class="ml-3 text-left hidden md:block">
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
                        <i class="fas fa-chevron-down ml-2 text-gray-500 text-sm"></i>
                    </div>
                </button>

                <!-- Dropdown menu -->
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-circle mr-3 text-gray-500"></i>
                            Profile
                        </a>

                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('classes.index') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-3 text-gray-500"></i>
                                Admin Settings
                            </a>
                        @endif

                        <div class="border-t border-gray-200"></div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
