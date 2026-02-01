@extends('layouts.admin')

@section('title', 'Assign Vouchers')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Assign Vouchers to PIC</h2>

    <!-- Available Stock Info -->
    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
        <p class="text-sm font-medium text-blue-900">
            Available Unassigned Vouchers: <span class="text-lg font-bold">{{ $availableCount }}</span>
        </p>
    </div>

    <form method="POST" action="{{ route('admin.vouchers.assign') }}" class="space-y-6">
        @csrf

        <!-- PIC Selection -->
        <div>
            <label for="pic_id" class="block text-sm font-medium text-gray-700">Select PIC</label>
            <select 
                name="pic_id" 
                id="pic_id" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('pic_id') border-red-500 @enderror"
                required
            >
                <option value="">-- Select PIC --</option>
                @foreach($pics as $pic)
                    <option value="{{ $pic->id }}" {{ old('pic_id') == $pic->id ? 'selected' : '' }}>
                        {{ $pic->name }} @if($pic->code)({{ $pic->code }})@endif
                    </option>
                @endforeach
            </select>
            @error('pic_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Batch Selection (Optional) -->
        <div>
            <label for="batch_id" class="block text-sm font-medium text-gray-700">Filter by Batch (Optional)</label>
            <select 
                name="batch_id" 
                id="batch_id" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
                <option value="">-- All Batches --</option>
                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                        {{ $batch->name }} ({{ $batch->generated_count }} vouchers)
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-sm text-gray-500">Leave empty to assign from any batch</p>
        </div>

        <!-- Quantity Input -->
        <div>
            <label for="qty" class="block text-sm font-medium text-gray-700">Number of Vouchers to Assign</label>
            <input 
                type="number" 
                name="qty" 
                id="qty" 
                min="1" 
                max="{{ $availableCount }}"
                value="{{ old('qty', 10) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('qty') border-red-500 @enderror"
                required
            >
            @error('qty')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">Maximum available: {{ $availableCount }}</p>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3">
            <button 
                type="submit"
                class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Assign Vouchers
            </button>
        </div>
    </form>
</div>
@endsection
