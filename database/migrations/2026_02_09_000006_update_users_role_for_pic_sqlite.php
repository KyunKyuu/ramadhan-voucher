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

        $columns = Schema::getColumnListing('users');

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('users_old');
        Schema::rename('users', 'users_old');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique('users_email_unique_v2');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['SUPERADMIN', 'MERCHANT', 'PIC'])->default('MERCHANT');
            $table->foreignId('merchant_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('pic_id')->nullable()->constrained('pics')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        $select = [
            'id',
            'name',
            'email',
            in_array('email_verified_at', $columns, true) ? 'email_verified_at' : DB::raw('NULL as email_verified_at'),
            'password',
            in_array('role', $columns, true) ? 'role' : DB::raw("'MERCHANT' as role"),
            in_array('merchant_id', $columns, true) ? 'merchant_id' : DB::raw('NULL as merchant_id'),
            in_array('pic_id', $columns, true) ? 'pic_id' : DB::raw('NULL as pic_id'),
            in_array('remember_token', $columns, true) ? 'remember_token' : DB::raw('NULL as remember_token'),
            'created_at',
            'updated_at',
            in_array('deleted_at', $columns, true) ? 'deleted_at' : DB::raw('NULL as deleted_at'),
        ];

        DB::table('users')->insertUsing(
            [
                'id',
                'name',
                'email',
                'email_verified_at',
                'password',
                'role',
                'merchant_id',
                'pic_id',
                'remember_token',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
            DB::table('users_old')->select($select)
        );

        Schema::drop('users_old');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        $columns = Schema::getColumnListing('users');

        Schema::disableForeignKeyConstraints();
        Schema::rename('users', 'users_old');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['SUPERADMIN', 'MERCHANT'])->default('MERCHANT');
            $table->foreignId('merchant_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('pic_id')->nullable()->constrained('pics')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        $select = [
            'id',
            'name',
            'email',
            in_array('email_verified_at', $columns, true) ? 'email_verified_at' : DB::raw('NULL as email_verified_at'),
            'password',
            in_array('role', $columns, true) ? 'role' : DB::raw("'MERCHANT' as role"),
            in_array('merchant_id', $columns, true) ? 'merchant_id' : DB::raw('NULL as merchant_id'),
            in_array('pic_id', $columns, true) ? 'pic_id' : DB::raw('NULL as pic_id'),
            in_array('remember_token', $columns, true) ? 'remember_token' : DB::raw('NULL as remember_token'),
            'created_at',
            'updated_at',
            in_array('deleted_at', $columns, true) ? 'deleted_at' : DB::raw('NULL as deleted_at'),
        ];

        DB::table('users')->insertUsing(
            [
                'id',
                'name',
                'email',
                'email_verified_at',
                'password',
                'role',
                'merchant_id',
                'pic_id',
                'remember_token',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
            DB::table('users_old')->select($select)
        );

        Schema::drop('users_old');
        Schema::enableForeignKeyConstraints();
    }
};
