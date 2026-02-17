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
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        Schema::disableForeignKeyConstraints();

        $this->fixMerchantVouchersForeignKey();
        $this->fixVoucherBatchesForeignKey();

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op for SQLite repair migration.
    }

    protected function fixMerchantVouchersForeignKey(): void
    {
        $table = 'merchant_vouchers';
        $oldTable = 'merchant_vouchers_old_fk_fix';

        if (!Schema::hasTable($table) && !Schema::hasTable($oldTable)) {
            return;
        }

        if (!Schema::hasTable($oldTable) && Schema::hasTable($table)) {
            Schema::rename($table, $oldTable);
        }

        if (!Schema::hasTable($table)) {
            Schema::create($table, function (Blueprint $table) {
                $table->id();
                $table->foreignId('initial_voucher_id')->constrained()->onDelete('cascade');
                $table->foreignId('merchant_id')->constrained()->onDelete('cascade');
                $table->string('code', 16);
                $table->enum('status', ['ACTIVE', 'REDEEMED', 'VOID'])->default('ACTIVE');
                $table->timestamp('redeemed_at')->nullable();
                $table->foreignId('redeemed_by_merchant_user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        DB::table($table)->delete();

        if (Schema::hasTable($oldTable)) {
            DB::table($table)->insertUsing(
            [
                'id',
                'initial_voucher_id',
                'merchant_id',
                'code',
                'status',
                'redeemed_at',
                'redeemed_by_merchant_user_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
            DB::table($oldTable)->select(
                'id',
                'initial_voucher_id',
                'merchant_id',
                'code',
                'status',
                'redeemed_at',
                'redeemed_by_merchant_user_id',
                'created_at',
                'updated_at',
                'deleted_at',
            )
            );

            Schema::drop($oldTable);
        }

        DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS merchant_vouchers_code_unique ON merchant_vouchers (code)');
        DB::statement('CREATE INDEX IF NOT EXISTS merchant_vouchers_status_index ON merchant_vouchers (status)');
    }

    protected function fixVoucherBatchesForeignKey(): void
    {
        if (!Schema::hasTable('voucher_batches')) {
            return;
        }

        Schema::dropIfExists('voucher_batches_old_fk_fix');
        Schema::rename('voucher_batches', 'voucher_batches_old_fk_fix');

        Schema::create('voucher_batches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('generated_count')->default(0);
            $table->foreignId('created_by_admin_id')->constrained('users');
            $table->timestamps();
        });

        DB::table('voucher_batches')->insertUsing(
            [
                'id',
                'name',
                'generated_count',
                'created_by_admin_id',
                'created_at',
                'updated_at',
            ],
            DB::table('voucher_batches_old_fk_fix')->select(
                'id',
                'name',
                'generated_count',
                'created_by_admin_id',
                'created_at',
                'updated_at',
            )
        );

        Schema::drop('voucher_batches_old_fk_fix');
    }
};
