<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::transaction(function () {
            // Ambil semua attribute beserta group dan category terkaitnya
            $attributes = Attribute::with('group.category')->get();

            $sortOrders = [];

            foreach ($attributes as $attribute) {
                // Pastikan attribute memiliki group dan terhubung ke suatu kategori
                if (! $attribute->group || ! $attribute->group->category) {
                    continue;
                }

                $categoryId = $attribute->group->category_id;
                $attributeId = $attribute->id;

                // Inisialisasi/increment sort order per kategori
                if (! isset($sortOrders[$categoryId])) {
                    $sortOrders[$categoryId] = 1;
                } else {
                    $sortOrders[$categoryId]++;
                }

                /**
                 * Logika penentuan is_required & is_scoring (gaya Versus.com):
                 * 
                 * 1. is_required: Atribut dasar/penting seperti RAM, Penyimpanan, Kapasitas Baterai, 
                 *    Ukuran Layar, dan Resolusi wajib diisi untuk komparasi dasar.
                 * 2. is_scoring: Atribut yang bersifat kuantitatif / fitur penting yang memengaruhi 
                 *    perhitungan skor/perbandingan spesifikasi produk di Versus.com.
                 */
                $isRequired = in_array($attribute->slug, [
                    'ram',
                    'internal_storage',
                    'battery_capacity',
                    'screen_size',
                    'resolution',
                    'processor',
                    'laptop_ram',
                    'laptop_storage',
                    'laptop_screen_size',
                    'fridge_capacity',
                    'washer_capacity',
                ]);

                // Di Versus.com, atribut komparatif utama (is_comparable) turut menentukan perhitungan skor produk
                $isScoring = $attribute->is_comparable ?? true;

                DB::table('category_attributes')->updateOrInsert(
                    [
                        'category_id' => $categoryId,
                        'attribute_id' => $attributeId,
                    ],
                    [
                        'is_required' => $isRequired,
                        'is_scoring' => $isScoring,
                        'sort_order' => $sortOrders[$categoryId],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        });

        $this->command?->info('CategoryAttributeSeeder berhasil dijalankan.');
    }
}