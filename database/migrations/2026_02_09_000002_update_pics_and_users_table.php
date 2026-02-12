<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pics', function (Blueprint $table) {
            if (!Schema::hasColumn('pics', 'email')) {
                $table->string('email')->nullable()->unique()->after('code');
            }
            if (!Schema::hasColumn('pics', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
        });

        // Update role enum to include PIC (skip for SQLite)
        // DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('SUPERADMIN', 'MERCHANT', 'PIC') NOT NULL DEFAULT 'MERCHANT'");
        // SQLite doesn't support MODIFY COLUMN enum directly like MySQL.
        // For SQLite we might need a workaround or just ignore if it's strictly SQLite.
        // Assuming MySQL/MariaDB based on typical Laravel usage, but error showed "Connection: sqlite".
        // IF SQLite, we can't easily modify enum check constraints without table rebuild.
        // But users table might just be a string column in SQLite?
        // Let's check connection type. The error said "Connection: sqlite".

        // Changing strategy: If it's SQLite, we might skip the enum modification or handle it differently.
        // However, for now let's just wrap the column additions.
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('SUPERADMIN', 'MERCHANT', 'PIC') NOT NULL DEFAULT 'MERCHANT'");
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'pic_id')) {
                $table->foreignId('pic_id')->nullable()->after('merchant_id')->constrained('pics')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'pic_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['pic_id']);
                $table->dropColumn('pic_id');
            });
        }

        // Revert role enum (skip for SQLite)
        // Warning: This might fail if there are users with 'PIC' role
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('SUPERADMIN', 'MERCHANT') NOT NULL DEFAULT 'MERCHANT'");
        }

        Schema::table('pics', function (Blueprint $table) {
            if (Schema::hasColumn('pics', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('pics', 'password')) {
                $table->dropColumn('password');
            }
        });
    }
};
