<?php

namespace Database\Seeders;

use App\Models\VoucherBatch;
use App\Models\Pic;
use App\Models\InitialVoucher;
use App\Models\Claim;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DashboardDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Ensure Admin Exists
        $admin = \App\Models\User::where('role', 'SUPERADMIN')->first() ?? \App\Models\User::create([
            'name' => 'Admin Seeder',
            'email' => 'admin_seed@example.com',
            'password' => bcrypt('password'),
            'role' => 'SUPERADMIN'
        ]);

        // 1. Ensure Batch Exists
        $batch = VoucherBatch::firstOrCreate(
            ['name' => 'Batch Seed 001'],
            [
                'created_by_admin_id' => $admin->id,
                'generated_count' => 1000
            ]
        );

        // 2. Ensure PIC Exists
        $pic = Pic::firstOrCreate(
            ['email' => 'pic_seed@example.com'],
            [
                'name' => 'PIC Seeder',
                'password' => bcrypt('password'),
                'is_active' => true,
                'code' => 'PIC-SEED'
            ]
        );

        // Ensure PIC User Exists
        \App\Models\User::firstOrCreate(
            ['email' => $pic->email],
            [
                'name' => $pic->name,
                'password' => $pic->password,
                'role' => 'PIC',
                'pic_id' => $pic->id,
            ]
        );

        // 3. Generate Claims over last 30 days
        $startDate = now()->subDays(30);

        for ($i = 0; $i <= 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            
            // Random number of claims per day (0 to 5)
            $dailyClaimsCount = rand(0, 5);

            for ($j = 0; $j < $dailyClaimsCount; $j++) {
                // Create Initial Voucher
                $voucher = InitialVoucher::create([
                    'code' => 'S' . $date->format('ymd') . '-' . $j . Str::random(3),
                    'batch_id' => $batch->id,
                    'assigned_pic_id' => $pic->id,
                    'status' => 'CLAIMED',
                    'claimed_at' => $date->setTime(rand(8, 20), rand(0, 59)),
                ]);

                // Create Claim
                Claim::create([
                    'initial_voucher_id' => $voucher->id,
                    'name' => 'Claimant ' . Str::random(5),
                    'email' => 'claimant_' . Str::random(5) . '@example.com',
                    'phone' => '0812' . rand(10000000, 99999999),
                    'zakat_fitrah_amount' => rand(0, 1) ? rand(35000, 150000) : 0,
                    'zakat_mal_amount' => rand(0, 1) ? rand(50000, 300000) : 0,
                    'infaq_amount' => rand(0, 1) ? rand(10000, 50000) : 0,
                    'sodaqoh_amount' => rand(0, 1) ? rand(5000, 100000) : 0,
                    'public_token' => Str::random(32),
                    'created_at' => $voucher->claimed_at,
                    'updated_at' => $voucher->claimed_at,
                ]);
            }
        }

        $this->command->info('Dashboard data seeded successfully!');
    }
}
