@extends('layouts.public')

@section('title', 'Voucher Tidak Valid')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden p-8 text-center">
        <!-- Error Icon -->
        <div class="text-6xl mb-4">❌</div>
        
        <!-- Error Title -->
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Voucher Tidak Valid</h2>
        
        <!-- Error Message -->
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <p>{{ $error }}</p>
        </div>

        <!-- Voucher Code -->
        <div class="mb-6">
            <p class="text-sm text-gray-500 mb-1">Kode yang Anda masukkan:</p>
            <p class="font-mono text-lg font-bold text-gray-900">{{ $code }}</p>
        </div>

        <!-- Help Text -->
        <div class="text-sm text-gray-600 space-y-2">
            <p><strong>Kemungkinan penyebab:</strong></p>
            <ul class="text-left list-disc list-inside space-y-1">
                <li>Voucher sudah pernah diklaim</li>
                <li>Voucher belum di-assign ke PIC</li>
                <li>Kode voucher salah atau tidak valid</li>
            </ul>
        </div>

        <!-- Contact Info -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">
                Jika Anda yakin ini adalah kesalahan, silakan hubungi PIC yang memberikan voucher ini.
            </p>
        </div>
    </div>
</div>
@endsection
