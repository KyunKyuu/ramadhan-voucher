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
            $table->enum('verification_status', ['PENDING', 'VERIFIED', 'ANOMALY'])->default('PENDING')->after('sodaqoh_amount');
            $table->text('verification_note')->nullable()->after('verification_status');
            $table->timestamp('verified_at')->nullable()->after('verification_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['verification_status', 'verification_note', 'verified_at']);
        });
    }
};
