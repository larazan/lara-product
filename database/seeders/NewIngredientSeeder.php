<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $ingredients = [
            [
                'name' => json_encode([
                    'en' => 'Niacinamide',
                    'id' => 'Niacinamide',
                ]),
                'inci_name' => 'Niacinamide',
                'safety_rating' => 5,
                'description' => json_encode([
                    'en' => 'Helps maintain the skin barrier, evens out skin tone, and controls sebum production.',
                    'id' => 'Membantu menjaga skin barrier, meratakan warna kulit, dan mengontrol produksi sebum.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Glycerin',
                    'id' => 'Glycerin',
                ]),
                'inci_name' => 'Glycerin',
                'safety_rating' => 5,
                'description' => json_encode([
                    'en' => 'Humectant that helps draw and retain skin moisture.',
                    'id' => 'Humektan yang membantu menarik dan mempertahankan kelembapan kulit.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Sodium Chloride',
                    'id' => 'Sodium Chloride',
                ]),
                'inci_name' => 'Sodium Chloride',
                'safety_rating' => 5,
                'description' => json_encode([
                    'en' => 'Used as a viscosity regulating and supportive ingredient in cosmetic formulations.',
                    'id' => 'Digunakan sebagai pengatur viskositas dan bahan pendukung formulasi kosmetik.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Hyaluronic Acid',
                    'id' => 'Hyaluronic Acid',
                ]),
                'inci_name' => 'Hyaluronic Acid',
                'safety_rating' => 5,
                'description' => json_encode([
                    'en' => 'Humectant ingredient that helps maintain skin hydration and moisture.',
                    'id' => 'Bahan humektan yang membantu menjaga hidrasi dan kelembapan kulit.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Salicylic Acid',
                    'id' => 'Salicylic Acid',
                ]),
                'inci_name' => 'Salicylic Acid',
                'safety_rating' => 3,
                'description' => json_encode([
                    'en' => 'BHA that helps exfoliate the skin and clear pores. Safety depends on concentration and usage.',
                    'id' => 'BHA yang membantu eksfoliasi kulit dan membersihkan pori-pori. Keamanan bergantung pada konsentrasi dan penggunaan.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Glycolic Acid',
                    'id' => 'Glycolic Acid',
                ]),
                'inci_name' => 'Glycolic Acid',
                'safety_rating' => 3,
                'description' => json_encode([
                    'en' => 'AHA that helps exfoliate the skin surface and improve skin texture.',
                    'id' => 'AHA yang membantu eksfoliasi permukaan kulit dan memperbaiki tekstur kulit.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Lactic Acid',
                    'id' => 'Lactic Acid',
                ]),
                'inci_name' => 'Lactic Acid',
                'safety_rating' => 4,
                'description' => json_encode([
                    'en' => 'AHA used for exfoliation and helping maintain skin moisture.',
                    'id' => 'AHA yang digunakan untuk eksfoliasi dan membantu menjaga kelembapan kulit.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Retinol',
                    'id' => 'Retinol',
                ]),
                'inci_name' => 'Retinol',
                'safety_rating' => 3,
                'description' => json_encode([
                    'en' => 'Vitamin A derivative that helps improve the appearance of skin texture and signs of aging.',
                    'id' => 'Turunan vitamin A yang membantu memperbaiki tampilan tekstur dan tanda penuaan kulit.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Ascorbic Acid',
                    'id' => 'Ascorbic Acid',
                ]),
                'inci_name' => 'Ascorbic Acid',
                'safety_rating' => 4,
                'description' => json_encode([
                    'en' => 'Form of vitamin C that acts as an antioxidant and helps brighten the appearance of the skin.',
                    'id' => 'Bentuk vitamin C yang berfungsi sebagai antioksidan dan membantu mencerahkan tampilan kulit.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Tocopherol',
                    'id' => 'Tocopherol',
                ]),
                'inci_name' => 'Tocopherol',
                'safety_rating' => 5,
                'description' => json_encode([
                    'en' => 'Form of vitamin E that acts as an antioxidant in cosmetic formulations.',
                    'id' => 'Bentuk vitamin E yang berfungsi sebagai antioksidan dalam formulasi kosmetik.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Panthenol',
                    'id' => 'Panthenol',
                ]),
                'inci_name' => 'Panthenol',
                'safety_rating' => 5,
                'description' => json_encode([
                    'en' => 'Provitamin B5 that helps maintain skin moisture and skin barrier condition.',
                    'id' => 'Provitamin B5 yang membantu menjaga kelembapan dan kondisi skin barrier.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Ceramide NP',
                    'id' => 'Ceramide NP',
                ]),
                'inci_name' => 'Ceramide NP',
                'safety_rating' => 5,
                'description' => json_encode([
                    'en' => 'Lipid that helps support skin protective function and retain moisture.',
                    'id' => 'Lipid yang membantu mendukung fungsi pelindung kulit dan mempertahankan kelembapan.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Dimethicone',
                    'id' => 'Dimethicone',
                ]),
                'inci_name' => 'Dimethicone',
                'safety_rating' => 5,
                'description' => json_encode([
                    'en' => 'Silicone ingredient that helps soften skin and form a protective layer.',
                    'id' => 'Bahan silikon yang membantu melembutkan kulit dan membentuk lapisan pelindung.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Petrolatum',
                    'id' => 'Petrolatum',
                ]),
                'inci_name' => 'Petrolatum',
                'safety_rating' => 5,
                'description' => json_encode([
                    'en' => 'Occlusive ingredient that helps reduce water loss from the skin surface.',
                    'id' => 'Bahan oklusif yang membantu mengurangi kehilangan air dari permukaan kulit.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Zinc Oxide',
                    'id' => 'Zinc Oxide',
                ]),
                'inci_name' => 'Zinc Oxide',
                'safety_rating' => 5,
                'description' => json_encode([
                    'en' => 'Used as a UV filter and skin protective ingredient in certain formulations.',
                    'id' => 'Digunakan sebagai filter UV dan bahan pelindung kulit pada formulasi tertentu.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Titanium Dioxide',
                    'id' => 'Titanium Dioxide',
                ]),
                'inci_name' => 'Titanium Dioxide',
                'safety_rating' => 5,
                'description' => json_encode([
                    'en' => 'Mineral pigment also used as a UV filter in certain cosmetic products.',
                    'id' => 'Pigmen mineral yang juga digunakan sebagai filter UV pada produk kosmetik tertentu.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Benzoyl Peroxide',
                    'id' => 'Benzoyl Peroxide',
                ]),
                'inci_name' => 'Benzoyl Peroxide',
                'safety_rating' => 3,
                'description' => json_encode([
                    'en' => 'Ingredient used in anti-acne products; can cause irritation and dry skin.',
                    'id' => 'Bahan yang digunakan dalam produk antiacne; dapat menyebabkan iritasi dan kulit kering.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Fragrance',
                    'id' => 'Pewangi',
                ]),
                'inci_name' => 'Parfum',
                'safety_rating' => 3,
                'description' => json_encode([
                    'en' => 'Blend of fragrance ingredients to give scent to the product. Allergy potential depends on composition.',
                    'id' => 'Campuran bahan pewangi untuk memberikan aroma pada produk. Potensi alergi bergantung pada komposisinya.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Phenoxyethanol',
                    'id' => 'Phenoxyethanol',
                ]),
                'inci_name' => 'Phenoxyethanol',
                'safety_rating' => 4,
                'description' => json_encode([
                    'en' => 'Preservative ingredient that helps prevent microorganism growth in cosmetics.',
                    'id' => 'Bahan pengawet yang membantu mencegah pertumbuhan mikroorganisme dalam kosmetik.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Sodium Hyaluronate',
                    'id' => 'Sodium Hyaluronate',
                ]),
                'inci_name' => 'Sodium Hyaluronate',
                'safety_rating' => 5,
                'description' => json_encode([
                    'en' => 'Sodium salt of hyaluronic acid used as a humectant.',
                    'id' => 'Garam natrium dari asam hialuronat yang digunakan sebagai humektan.',
                ]),
            ],
            [
                'name' => json_encode([
                    'en' => 'Cocamidopropyl Betaine',
                    'id' => 'Cocamidopropyl Betaine',
                ]),
                'inci_name' => 'Cocamidopropyl Betaine',
                'safety_rating' => 4,
                'description' => json_encode([
                    'en' => 'Surfactant that helps cleanse and create foam in cleansing products.',
                    'id' => 'Surfaktan yang membantu membersihkan dan menghasilkan busa pada produk pembersih.',
                ]),
            ],
        ];

        $now = now();

        foreach ($ingredients as &$ingredient) {
            $ingredient['created_at'] = $now;
            $ingredient['updated_at'] = $now;
        }

        unset($ingredient);

        // Menggunakan 'inci_name' sebagai unique key karena kolom string, 
        // serta mengubah array menjadi json_encode jika kolom di database bertipe JSON/TEXT.
        // (Catatan: Jika model Anda menggunakan $casts array untuk 'name' dan 'description', 
        //  pastikan database mendukung tipe JSON, atau biarkan berbentuk array jika menggunakan Eloquent Create/Insert biasa).

        DB::table('ingredients')->upsert(
            $ingredients,
            ['inci_name'], // Kolom unik untuk acuan upsert
            ['name', 'safety_rating', 'description', 'updated_at']
        );
    }
}
