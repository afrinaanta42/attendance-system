<!-- Footer -->
<footer class="bg-white border-t border-gray-200 py-4">
    <div class="max-w-8xl mx-auto px-6">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                &copy; {{ date('Y') }} {{ config('app.name', ' - Attendance System') }}. All rights reserved.
            </div>
            <div class="flex items-center space-x-4">
                <a href="/terms" class="text-sm text-gray-600 hover:text-gray-900">Terms</a>
                <a href="/privacy" class="text-sm text-gray-600 hover:text-gray-900">Privacy</a>
                <span class="text-sm text-gray-500">v1.0.0</span>
            </div>
        </div>
    </div>
</footer>
