<header class="bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-4 py-3">
        <!-- Left side -->
        <div class="flex items-center">
            <!-- Page title -->
            <h1 class="text-lg font-semibold text-gray-900">
                @hasSection('title')
                    @yield('title')
                @else
                    Dashboard
                @endif
            </h1>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-bell"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-primary rounded-full"></span>
            </button>

            <!-- User dropdown -->
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3 focus:outline-none">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-r from-primary to-primary/90 flex items-center justify-center">
                            <span class="text-white text-xs font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </span>
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">
                                @if (auth()->user()->isAdmin())
                                    Admin
                                @elseif(auth()->user()->isTeacher())
                                    Teacher
                                @elseif(auth()->user()->isStudent())
                                    Student
                                @endif
                            </p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-500 text-sm"></i>
                    </div>
                </button>

                <!-- Dropdown menu -->
                <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary">
                        <i class="fas fa-user-circle mr-2"></i> Profile
                    </a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-red-600">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
