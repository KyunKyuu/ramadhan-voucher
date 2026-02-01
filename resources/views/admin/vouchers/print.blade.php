@extends('layouts.admin')

@section('title', 'Print Vouchers')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Print Vouchers</h2>

    <form method="GET" action="{{ route('admin.vouchers.print.preview') }}" class="space-y-6">
        <!-- PIC Filter -->
        <div>
            <label for="pic_id" class="block text-sm font-medium text-gray-700">Filter by PIC (Optional)</label>
            <select 
                name="pic_id" 
                id="pic_id" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
                <option value="">-- All PICs --</option>
                @foreach($pics as $pic)
                    <option value="{{ $pic->id }}">{{ $pic->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Batch Filter -->
        <div>
            <label for="batch_id" class="block text-sm font-medium text-gray-700">Filter by Batch (Optional)</label>
            <select 
                name="batch_id" 
                id="batch_id" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
                <option value="">-- All Batches --</option>
                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Status Filter -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Filter by Status</label>
            <select 
                name="status" 
                id="status" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
                <option value="">-- All Statuses --</option>
                <option value="ASSIGNED" selected>ASSIGNED (Recommended)</option>
                <option value="UNASSIGNED">UNASSIGNED</option>
                <option value="CLAIMED">CLAIMED</option>
            </select>
            <p class="mt-1 text-sm text-gray-500">Default: ASSIGNED vouchers only</p>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3">
            <button 
                type="submit"
                class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Generate PDF
            </button>
        </div>
    </form>

    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
        <h3 class="text-sm font-medium text-gray-900 mb-2">Print Specifications:</h3>
        <ul class="text-sm text-gray-600 space-y-1">
            <li>• Paper: A4 Portrait (210 × 297mm)</li>
            <li>• Layout: 3 vouchers per page (stacked vertically)</li>
            <li>• Each voucher contains: QR code, voucher code, PIC name</li>
            <li>• QR code links to: {{ config('app.url') }}/claim/{code}</li>
        </ul>
    </div>
</div>
@endsection
