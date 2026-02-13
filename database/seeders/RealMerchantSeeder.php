<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\MerchantOffer;
use App\Models\MerchantOfferImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealMerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchants = [
            [
                'name' => 'MBN OPTIC',
                'address' => 'Jl. Pamekar Raya No.28 panghegar permai',
                'website' => 'https://instagram.com/mbnoptic',
                'logo_url' => '/images/logo/mbn.png', // Placeholder extension
                'offer_title' => 'Selected Lensa Disc 50% & All Frame 10%',
                'offer_description' => "Selected lensa Disc 50%:\n- Lensa Blu Comfort\n- Lensa Blu Evo Comfort\n- Lensa Progressive Konvensional\n\nAll Frame 10%",
                'discount_value' => 50, // Taking the max discount as representative
                'discount_type' => 'PERCENT'
            ],
            [
                'name' => 'Geprekan Maspur Geger Kalong',
                'address' => 'Jl. Gegerkalong Hilir No. 30 Samping AHAS Honda',
                'website' => null,
                'logo_url' => '/images/logo/geprek.png',
                'offer_title' => 'Nasi Ayam Geprek All Variant',
                'offer_description' => 'Nasi Ayam Geprek All Variant 13k (up 10% discount)',
                'discount_value' => 10,
                'discount_type' => 'PERCENT'
            ],
            [
                'name' => 'Kerudung Pashmina dan Segi Empat Premium',
                'address' => null, // Not provided
                'website' => 'https://share.google/HWkcNhxf7GGZmxsz0',
                'logo_url' => '/images/logo/rg.png',
                'offer_title' => 'Aneka Menu & Fashion Discount',
                'offer_description' => "1. Kerudung Segi 4 Premium\nBahan polycotton, tegak paripurna.\nHarga jual 36.000 -> Discount 20% jadi 28.000\n\n2. Kerudung Pashmina Premium\nBahan Jersey premium, adem dan jatuh.\nHarga jual 36.000 -> Discount 20% jadi 28.000\n\n3. Ricebowl\nIsian menu: nasi, mie goreng, tenggiri Krispy, nugget, ayam Krispy, sambel, lalap bonteng (bebas request dan pilih isian).\nHarga jual 17.000 -> Discount 30% jadi 12.000\nNote: Open PO\n\n4. Paket Snack Box\nIsian Menu: Aqua gelas, Lontong, Bala2, Gehu goreng, Tempe goreng, Pisang goreng, Ubi goreng, Piscok, Karoket, Martabak tahu, Cireng, Onde, Roti goreng isi coklat, Tahu walik, Pastel, Ulen, Kue putu ayu.\nMin beli 15 box, harga jual per box tergantung isi box.\nDiscount 30%\nNote: Open PO",
                'discount_value' => 30, // Max discount
                'discount_type' => 'PERCENT'
            ],
            [
                'name' => 'hmshn.stdious X prcs.orgnl',
                'address' => 'Jln Babakan Tarogong No 147',
                'website' => 'https://instagram.com/homeshine.studious',
                'logo_url' => '/images/logo/hm.png',
                'offer_title' => 'Semua Artikel Discount 25%',
                'offer_description' => 'Semua artikel discount 25%',
                'discount_value' => 25,
                'discount_type' => 'PERCENT'
            ],
        ];

        foreach ($merchants as $data) {
            $merchant = Merchant::firstOrCreate(
                ['email' => Str::slug($data['name']) . '@example.com'],
                [
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']),
                    'password' => bcrypt('password'),
                    'is_active' => true,
                    'address' => $data['address'],
                    'website' => $data['website'],
                    'logo_url' => $data['logo_url'],
                    'voucher_template' => 'baju.jpeg', // Default template
                ]
            );

            MerchantOffer::updateOrCreate(
                ['merchant_id' => $merchant->id],
                [
                    'title' => $data['offer_title'],
                    'description' => $data['offer_description'],
                    'discount_type' => $data['discount_type'],
                    'discount_value' => $data['discount_value'],
                    'is_active' => true,
                ]
            );

            // Ensure Merchant User Exists
            \App\Models\User::firstOrCreate(
                ['email' => $merchant->email],
                [
                    'name' => $merchant->name,
                    'password' => $merchant->password,
                    'role' => 'MERCHANT',
                    'merchant_id' => $merchant->id,
                ]
            );
        }

        $this->command->info('Real merchants seeded successfully!');
    }
}
