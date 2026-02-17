<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('claims', 'pic_id')) {
            Schema::table('claims', function (Blueprint $table) {
                $table->unsignedBigInteger('pic_id')->nullable()->after('initial_voucher_id');
                $table->index('pic_id');
            });
        }

        // Backfill pic_id from assigned PIC on initial_vouchers for existing rows.
        DB::statement('
            UPDATE claims
            SET pic_id = (
                SELECT initial_vouchers.assigned_pic_id
                FROM initial_vouchers
                WHERE initial_vouchers.id = claims.initial_voucher_id
            )
            WHERE pic_id IS NULL
        ');

        // Add FK only for drivers that support adding FK reliably via alter table.
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('claims', function (Blueprint $table) {
                $table->foreign('pic_id')->references('id')->on('pics')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('claims', 'pic_id')) {
            return;
        }

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('claims', function (Blueprint $table) {
                $table->dropForeign(['pic_id']);
            });
        }

        Schema::table('claims', function (Blueprint $table) {
            $table->dropIndex(['pic_id']);
            $table->dropColumn('pic_id');
        });
    }
};
