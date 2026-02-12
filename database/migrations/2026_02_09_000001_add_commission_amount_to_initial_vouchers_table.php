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
        Schema::table('initial_vouchers', function (Blueprint $table) {
            $table->decimal('commission_amount', 12, 2)->default(0)->after('assigned_pic_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('initial_vouchers', function (Blueprint $table) {
            $table->dropColumn('commission_amount');
        });
    }
};
