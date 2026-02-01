@extends('layouts.admin')

@section('title', 'Generate Vouchers')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Generate Vouchers</h2>

    <form method="POST" action="{{ route('admin.vouchers.generate') }}" class="space-y-6">
        @csrf

        <!-- Count Input -->
        <div>
            <label for="count" class="block text-sm font-medium text-gray-700">Number of Vouchers</label>
            <input 
                type="number" 
                name="count" 
                id="count" 
                min="1" 
                max="1000"
                value="{{ old('count', 10) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('count') border-red-500 @enderror"
                required
            >
            @error('count')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">Maximum: 1000 vouchers per batch</p>
        </div>

        <!-- Batch Name Input -->
        <div>
            <label for="batch_name" class="block text-sm font-medium text-gray-700">Batch Name (Optional)</label>
            <input 
                type="text" 
                name="batch_name" 
                id="batch_name" 
                value="{{ old('batch_name') }}"
                placeholder="e.g., Ramadhan Week 1"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('batch_name') border-red-500 @enderror"
            >
            @error('batch_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">If empty, will auto-generate based on current date/time</p>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3">
            <button 
                type="submit"
                class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Generate Vouchers
            </button>
        </div>
    </form>
</div>
@endsection
