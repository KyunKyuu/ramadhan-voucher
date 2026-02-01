<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('merchant_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('initial_voucher_id')->constrained()->onDelete('cascade');
            $table->foreignId('merchant_id')->constrained()->onDelete('cascade');
            $table->string('code', 16)->unique();
            $table->enum('status', ['ACTIVE', 'REDEEMED', 'VOID'])->default('ACTIVE');
            $table->timestamp('redeemed_at')->nullable();
            $table->foreignId('redeemed_by_merchant_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_vouchers');
    }
};
