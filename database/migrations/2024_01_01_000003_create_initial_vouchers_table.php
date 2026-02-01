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
        Schema::create('initial_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->nullable()->constrained('voucher_batches')->onDelete('set null');
            $table->string('code', 16)->unique();
            $table->enum('status', ['UNASSIGNED', 'ASSIGNED', 'CLAIMED', 'VOID'])->default('UNASSIGNED');
            $table->foreignId('assigned_pic_id')->nullable()->constrained('pics')->onDelete('set null');
            $table->timestamp('claimed_at')->nullable();
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
        Schema::dropIfExists('initial_vouchers');
    }
};
