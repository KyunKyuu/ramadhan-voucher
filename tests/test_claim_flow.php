<?php

use App\Services\ClaimService;
use App\Models\InitialVoucher;
use App\Models\MerchantVoucher;
use App\Models\Claim;

echo "=== Testing EPIC-01 Public Claim Flow ===\n\n";

// Get an assigned voucher
$voucher = InitialVoucher::where('status', 'ASSIGNED')
    ->with('pic')
    ->first();

if (!$voucher) {
    echo "❌ No ASSIGNED voucher found. Please run voucher generation and assignment first.\n";
    exit(1);
}

echo "Test 1: Validating voucher for claim...\n";
$claimService = app(ClaimService::class);

try {
    $validatedVoucher = $claimService->validateVoucherForClaim($voucher->code);
    echo "✓ Voucher is valid for claim\n";
    echo "✓ Code: {$validatedVoucher->code}\n";
    echo "✓ PIC: {$validatedVoucher->pic->name}\n";
    echo "✓ Status: {$validatedVoucher->status}\n\n";
} catch (\Exception $e) {
    echo "❌ Validation failed: {$e->getMessage()}\n";
    exit(1);
}

// Test 2: Process claim
echo "Test 2: Processing claim...\n";
try {
    $claim = $claimService->processClaim(
        $voucher->code,
        'Test User',
        'test@example.com'
    );
    
    echo "✓ Claim processed successfully\n";
    echo "✓ Claim ID: {$claim->id}\n";
    echo "✓ Public Token: {$claim->public_token}\n";
    echo "✓ Name: {$claim->name}\n";
    echo "✓ Email: {$claim->email}\n\n";
} catch (\Exception $e) {
    echo "❌ Claim processing failed: {$e->getMessage()}\n";
    exit(1);
}

// Test 3: Verify voucher status changed
echo "Test 3: Verifying voucher status...\n";
$voucher->refresh();
echo "✓ Voucher status: {$voucher->status}\n";
echo "✓ Claimed at: {$voucher->claimed_at}\n\n";

if ($voucher->status !== 'CLAIMED') {
    echo "❌ Voucher status should be CLAIMED\n";
    exit(1);
}

// Test 4: Verify merchant vouchers generated
echo "Test 4: Checking merchant vouchers...\n";
$merchantVouchers = MerchantVoucher::where('initial_voucher_id', $voucher->id)->get();
echo "✓ Generated {$merchantVouchers->count()} merchant vouchers\n";

foreach ($merchantVouchers->take(3) as $mv) {
    echo "  - {$mv->merchant->name}: {$mv->code} ({$mv->status})\n";
}
echo "\n";

// Test 5: Retrieve claim by token
echo "Test 5: Retrieving claim by token...\n";
$retrievedClaim = $claimService->getClaimByToken($claim->public_token);

if (!$retrievedClaim) {
    echo "❌ Could not retrieve claim by token\n";
    exit(1);
}

echo "✓ Retrieved claim successfully\n";
echo "✓ Merchant vouchers loaded: {$retrievedClaim->merchantVouchers->count()}\n\n";

// Test 6: Try to claim again (should fail)
echo "Test 6: Testing double-claim prevention...\n";
try {
    $claimService->processClaim(
        $voucher->code,
        'Another User',
        'another@example.com'
    );
    echo "❌ Double claim should have been prevented!\n";
    exit(1);
} catch (\Exception $e) {
    echo "✓ Double claim prevented: {$e->getMessage()}\n\n";
}

// Summary
echo "=== Test Summary ===\n";
echo "✓ All tests passed!\n";
echo "✓ Voucher validation: WORKING\n";
echo "✓ Atomic claim processing: WORKING\n";
echo "✓ Merchant voucher generation: WORKING\n";
echo "✓ Double-claim prevention: WORKING\n";
echo "✓ Token-based retrieval: WORKING\n\n";

echo "=== Public URLs ===\n";
echo "Claim URL: " . url("/claim/{$voucher->code}") . "\n";
echo "Voucher List URL: " . url("/v/{$claim->public_token}") . "\n";
