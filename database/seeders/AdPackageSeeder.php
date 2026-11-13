<?php

namespace Database\Seeders;

use App\Models\AdPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $packages = [
            [
                'name' => 'Starter',
                'token_amount' => 1000,
                'price' => 100000,
                'currency' => 'IDR',
                'description' => 'Paket awal untuk mencoba beriklan di platform.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Basic',
                'token_amount' => 5000,
                'price' => 450000,
                'currency' => 'IDR',
                'description' => 'Paket iklan untuk bisnis kecil.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Professional',
                'token_amount' => 10000,
                'price' => 850000,
                'currency' => 'IDR',
                'description' => 'Paket iklan untuk bisnis yang sedang berkembang.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Business',
                'token_amount' => 25000,
                'price' => 2000000,
                'currency' => 'IDR',
                'description' => 'Paket iklan untuk bisnis dengan jangkauan lebih luas.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Enterprise',
                'token_amount' => 50000,
                'price' => 3750000,
                'currency' => 'IDR',
                'description' => 'Paket terbaik untuk pengiklan dengan kebutuhan kampanye besar.',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($packages as $package) {
            AdPackage::updateOrCreate(
                ['name' => $package['name']],
                $package
            );
        }
    }
}
