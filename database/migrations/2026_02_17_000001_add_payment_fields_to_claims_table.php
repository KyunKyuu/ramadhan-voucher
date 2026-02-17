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
        Schema::table('claims', function (Blueprint $table) {
            $table->string('payment_method', 20)->default('cash')->after('sodaqoh_amount');
            $table->string('transfer_destination')->nullable()->after('payment_method');
            $table->string('transfer_proof_path')->nullable()->after('transfer_destination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'transfer_destination',
                'transfer_proof_path',
            ]);
        });
    }
};
