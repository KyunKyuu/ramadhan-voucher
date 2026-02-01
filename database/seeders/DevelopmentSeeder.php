<?php

namespace Database\Seeders;

use App\Models\Pic;
use App\Models\Merchant;
use App\Models\MerchantOffer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create SuperAdmin user
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@ramadhan.test',
            'password' => Hash::make('password'),
            'role' => 'SUPERADMIN',
        ]);

        echo "✓ Created SuperAdmin: admin@ramadhan.test / password\n";

        // Create PICs
        $pics = [
            ['name' => 'Ahmad Yusuf', 'code' => 'PIC001', 'is_active' => true],
            ['name' => 'Fatimah Zahra', 'code' => 'PIC002', 'is_active' => true],
            ['name' => 'Muhammad Ali', 'code' => 'PIC003', 'is_active' => true],
            ['name' => 'Khadijah Binti', 'code' => 'PIC004', 'is_active' => true],
            ['name' => 'Umar Faruq', 'code' => 'PIC005', 'is_active' => false], // Inactive
        ];

        foreach ($pics as $picData) {
            Pic::create($picData);
        }

        echo "✓ Created " . count($pics) . " PICs\n";

        // Create Merchants with Offers
        $merchants = [
            [
                'name' => 'Warung Makan Sederhana',
                'slug' => 'warung-makan-sederhana',
                'email' => 'warung.makan@example.com',
                'password' => bcrypt('password123'),
                'logo_url' => null,
                'is_active' => true,
                'offer' => [
                    'title' => 'Diskon 20% untuk semua menu',
                    'discount_type' => 'PERCENT',
                    'discount_value' => 20,
                    'description' => 'Berlaku untuk semua menu makanan dan minuman',
                    'is_active' => true,
                ],
            ],
            [
                'name' => 'Toko Buku Berkah',
                'slug' => 'toko-buku-berkah',
                'email' => 'toko.buku@example.com',
                'password' => bcrypt('password123'),
                'logo_url' => null,
                'is_active' => true,
                'offer' => [
                    'title' => 'Potongan Rp 50.000',
                    'discount_type' => 'AMOUNT',
                    'discount_value' => 50000,
                    'description' => 'Untuk pembelian minimal Rp 200.000',
                    'is_active' => true,
                ],
            ],
            [
                'name' => 'Kafe Ramadhan',
                'slug' => 'kafe-ramadhan',
                'email' => 'kafe.ramadhan@example.com',
                'password' => bcrypt('password123'),
                'logo_url' => null,
                'is_active' => true,
                'offer' => [
                    'title' => 'Gratis 1 minuman (Diskon 100%)',
                    'discount_type' => 'PERCENT',
                    'discount_value' => 100,
                    'description' => 'Gratis 1 minuman untuk setiap pembelian',
                    'is_active' => true,
                ],
            ],
            [
                'name' => 'Toko Pakaian Muslim',
                'slug' => 'toko-pakaian-muslim',
                'email' => 'toko.pakaian@example.com',
                'password' => bcrypt('password123'),
                'logo_url' => null,
                'is_active' => true,
                'offer' => [
                    'title' => 'Diskon 15% semua produk',
                    'discount_type' => 'PERCENT',
                    'discount_value' => 15,
                    'description' => 'Berlaku untuk semua jenis pakaian muslim',
                    'is_active' => true,
                ],
            ],
            [
                'name' => 'Apotek Sehat',
                'slug' => 'apotek-sehat',
                'email' => 'apotek.sehat@example.com',
                'password' => bcrypt('password123'),
                'logo_url' => null,
                'is_active' => true,
                'offer' => [
                    'title' => 'Potongan Rp 25.000',
                    'discount_type' => 'AMOUNT',
                    'discount_value' => 25000,
                    'description' => 'Untuk pembelian obat dan vitamin',
                    'is_active' => true,
                ],
            ],
        ];

        foreach ($merchants as $merchantData) {
            $offerData = $merchantData['offer'];
            unset($merchantData['offer']);

            $merchant = Merchant::create($merchantData);
            
            MerchantOffer::create([
                'merchant_id' => $merchant->id,
                ...$offerData,
            ]);

            // Create merchant user
            User::create([
                'name' => $merchant->name . ' Staff',
                'email' => $merchant->email,
                'password' => $merchant->password,
                'role' => 'MERCHANT',
                'merchant_id' => $merchant->id,
            ]);
        }

        echo "✓ Created " . count($merchants) . " Merchants with offers and users\n";
        echo "\n";
        echo "=== Login Credentials ===\n";
        echo "SuperAdmin: admin@ramadhan.test / password\n";
        echo "Merchants: [merchant-email] / password123\n";
        echo "  Example: warung-makan-sederhana@merchant.test / password\n";
    }
}
