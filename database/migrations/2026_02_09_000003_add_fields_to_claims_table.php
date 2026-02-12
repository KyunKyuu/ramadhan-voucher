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
            $table->string('phone', 30)->nullable()->after('email');
            $table->decimal('zakat_fitrah_amount', 12, 2)->default(0)->after('phone');
            $table->decimal('infaq_amount', 12, 2)->default(0)->after('zakat_fitrah_amount');
            $table->decimal('sodaqoh_amount', 12, 2)->default(0)->after('infaq_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['phone', 'zakat_fitrah_amount', 'infaq_amount', 'sodaqoh_amount']);
        });
    }
};
