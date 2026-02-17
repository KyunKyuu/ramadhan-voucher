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

        // Ensure no orphan pic_id before FK creation (important for MySQL).
        if (Schema::hasTable('pics')) {
            if (DB::getDriverName() === 'mysql') {
                DB::statement('
                    UPDATE claims c
                    LEFT JOIN pics p ON p.id = c.pic_id
                    SET c.pic_id = NULL
                    WHERE c.pic_id IS NOT NULL
                      AND p.id IS NULL
                ');
            } else {
                DB::statement('
                    UPDATE claims
                    SET pic_id = NULL
                    WHERE pic_id IS NOT NULL
                      AND pic_id NOT IN (SELECT id FROM pics)
                ');
            }
        }

        // Add FK only for drivers that support adding FK reliably via alter table.
        if (
            DB::getDriverName() !== 'sqlite' &&
            Schema::hasTable('pics') &&
            !$this->foreignKeyExists('claims', 'claims_pic_id_foreign')
        ) {
            try {
                Schema::table('claims', function (Blueprint $table) {
                    $table->foreign('pic_id')->references('id')->on('pics')->nullOnDelete();
                });
            } catch (\Throwable $e) {
                // Keep migration non-blocking across environments with legacy schema differences.
            }
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

        if (DB::getDriverName() !== 'sqlite' && $this->foreignKeyExists('claims', 'claims_pic_id_foreign')) {
            Schema::table('claims', function (Blueprint $table) {
                $table->dropForeign(['pic_id']);
            });
        }

        Schema::table('claims', function (Blueprint $table) {
            $table->dropIndex(['pic_id']);
            $table->dropColumn('pic_id');
        });
    }

    protected function foreignKeyExists(string $tableName, string $constraintName): bool
    {
        if (DB::getDriverName() !== 'mysql') {
            return false;
        }

        $result = DB::selectOne(
            'SELECT COUNT(*) AS total
             FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND CONSTRAINT_NAME = ?
               AND CONSTRAINT_TYPE = "FOREIGN KEY"',
            [$tableName, $constraintName]
        );

        return (int) ($result->total ?? 0) > 0;
    }
};
