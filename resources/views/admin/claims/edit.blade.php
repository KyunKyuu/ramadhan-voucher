@extends('layouts.admin')

@section('title', 'Edit Claim')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.claims.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Claim</h2>
                    <p class="text-gray-600">Update details for claim #{{ $claim->id }}</p>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-md max-w-3xl">
            <form action="{{ route('admin.claims.update', $claim->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Donor Details -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Donor Information</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $claim->name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $claim->phone) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('phone') border-red-500 @enderror">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $claim->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Donation Amounts -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Donation Amounts (Rp)</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Zakat Fitrah</label>
                            <input type="number" name="zakat_fitrah_amount"
                                value="{{ old('zakat_fitrah_amount', $claim->zakat_fitrah_amount) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('zakat_fitrah_amount') border-red-500 @enderror">
                            @error('zakat_fitrah_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Zakat Mal</label>
                            <input type="number" name="zakat_mal_amount"
                                value="{{ old('zakat_mal_amount', $claim->zakat_mal_amount) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('zakat_mal_amount') border-red-500 @enderror">
                            @error('zakat_mal_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Infaq</label>
                            <input type="number" name="infaq_amount" value="{{ old('infaq_amount', $claim->infaq_amount) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('infaq_amount') border-red-500 @enderror">
                            @error('infaq_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sodaqoh</label>
                            <input type="number" name="sodaqoh_amount"
                                value="{{ old('sodaqoh_amount', $claim->sodaqoh_amount) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('sodaqoh_amount') border-red-500 @enderror">
                            @error('sodaqoh_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Readonly info -->
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Claim Read-only Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-semibold block">Voucher Code:</span>
                            {{ $claim->initialVoucher->code ?? 'N/A' }}
                        </div>
                        <div>
                            <span class="font-semibold block">Payment Method:</span>
                            {{ $claim->payment_method ?? 'N/A' }}
                        </div>
                        <div>
                            <span class="font-semibold block">Claim Date:</span>
                            {{ $claim->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t mt-6">
                    <a href="{{ route('admin.claims.index') }}"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection