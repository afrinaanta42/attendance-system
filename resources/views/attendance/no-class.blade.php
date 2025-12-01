{{-- resources/views/attendance/no-class.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-20 text-center">
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-6 py-4 rounded-lg">
            <h3 class="text-xl font-bold">No Class Assigned</h3>
            <p class="mt-2">You are not assigned to any class yet. Please contact Admin.</p>
            <a href="{{ route('dashboard') }}"
                class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Back to Dashboard
            </a>
        </div>
    </div>
@endsection
