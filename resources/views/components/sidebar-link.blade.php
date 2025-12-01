<!-- resources/views/components/sidebar-link.blade.php -->
@props(['href', 'active' => false])

<a href="{{ $href }}"
    class="flex items-center px-4 py-3 text-white rounded-lg transition {{ $active ? 'bg-indigo-700' : 'hover:bg-indigo-700' }}">
    {{ $slot }}
</a>
