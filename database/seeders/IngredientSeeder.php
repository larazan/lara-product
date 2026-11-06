<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $ingredients = [
            [
                'name' => 'Niacinamide',
                'inci_name' => 'Niacinamide',
                'safety_rating' => 5,
                'description' => 'Membantu menjaga skin barrier, meratakan warna kulit, dan mengontrol produksi sebum.',
            ],
            [
                'name' => 'Glycerin',
                'inci_name' => 'Glycerin',
                'safety_rating' => 5,
                'description' => 'Humektan yang membantu menarik dan mempertahankan kelembapan kulit.',
            ],
            [
                'name' => 'Sodium Chloride',
                'inci_name' => 'Sodium Chloride',
                'safety_rating' => 5,
                'description' => 'Digunakan sebagai pengatur viskositas dan bahan pendukung formulasi kosmetik.',
            ],
            [
                'name' => 'Hyaluronic Acid',
                'inci_name' => 'Hyaluronic Acid',
                'safety_rating' => 5,
                'description' => 'Bahan humektan yang membantu menjaga hidrasi dan kelembapan kulit.',
            ],
            [
                'name' => 'Salicylic Acid',
                'inci_name' => 'Salicylic Acid',
                'safety_rating' => 3,
                'description' => 'BHA yang membantu eksfoliasi kulit dan membersihkan pori-pori. Keamanan bergantung pada konsentrasi dan penggunaan.',
            ],
            [
                'name' => 'Glycolic Acid',
                'inci_name' => 'Glycolic Acid',
                'safety_rating' => 3,
                'description' => 'AHA yang membantu eksfoliasi permukaan kulit dan memperbaiki tekstur kulit.',
            ],
            [
                'name' => 'Lactic Acid',
                'inci_name' => 'Lactic Acid',
                'safety_rating' => 4,
                'description' => 'AHA yang digunakan untuk eksfoliasi dan membantu menjaga kelembapan kulit.',
            ],
            [
                'name' => 'Retinol',
                'inci_name' => 'Retinol',
                'safety_rating' => 3,
                'description' => 'Turunan vitamin A yang membantu memperbaiki tampilan tekstur dan tanda penuaan kulit.',
            ],
            [
                'name' => 'Ascorbic Acid',
                'inci_name' => 'Ascorbic Acid',
                'safety_rating' => 4,
                'description' => 'Bentuk vitamin C yang berfungsi sebagai antioksidan dan membantu mencerahkan tampilan kulit.',
            ],
            [
                'name' => 'Tocopherol',
                'inci_name' => 'Tocopherol',
                'safety_rating' => 5,
                'description' => 'Bentuk vitamin E yang berfungsi sebagai antioksidan dalam formulasi kosmetik.',
            ],
            [
                'name' => 'Panthenol',
                'inci_name' => 'Panthenol',
                'safety_rating' => 5,
                'description' => 'Provitamin B5 yang membantu menjaga kelembapan dan kondisi skin barrier.',
            ],
            [
                'name' => 'Ceramide NP',
                'inci_name' => 'Ceramide NP',
                'safety_rating' => 5,
                'description' => 'Lipid yang membantu mendukung fungsi pelindung kulit dan mempertahankan kelembapan.',
            ],
            [
                'name' => 'Dimethicone',
                'inci_name' => 'Dimethicone',
                'safety_rating' => 5,
                'description' => 'Bahan silikon yang membantu melembutkan kulit dan membentuk lapisan pelindung.',
            ],
            [
                'name' => 'Petrolatum',
                'inci_name' => 'Petrolatum',
                'safety_rating' => 5,
                'description' => 'Bahan oklusif yang membantu mengurangi kehilangan air dari permukaan kulit.',
            ],
            [
                'name' => 'Zinc Oxide',
                'inci_name' => 'Zinc Oxide',
                'safety_rating' => 5,
                'description' => 'Digunakan sebagai filter UV dan bahan pelindung kulit pada formulasi tertentu.',
            ],
            [
                'name' => 'Titanium Dioxide',
                'inci_name' => 'Titanium Dioxide',
                'safety_rating' => 5,
                'description' => 'Pigmen mineral yang juga digunakan sebagai filter UV pada produk kosmetik tertentu.',
            ],
            [
                'name' => 'Benzoyl Peroxide',
                'inci_name' => 'Benzoyl Peroxide',
                'safety_rating' => 3,
                'description' => 'Bahan yang digunakan dalam produk antiacne; dapat menyebabkan iritasi dan kulit kering.',
            ],
            [
                'name' => 'Fragrance',
                'inci_name' => 'Parfum',
                'safety_rating' => 3,
                'description' => 'Campuran bahan pewangi untuk memberikan aroma pada produk. Potensi alergi bergantung pada komposisinya.',
            ],
            [
                'name' => 'Phenoxyethanol',
                'inci_name' => 'Phenoxyethanol',
                'safety_rating' => 4,
                'description' => 'Bahan pengawet yang membantu mencegah pertumbuhan mikroorganisme dalam kosmetik.',
            ],
            [
                'name' => 'Sodium Hyaluronate',
                'inci_name' => 'Sodium Hyaluronate',
                'safety_rating' => 5,
                'description' => 'Garam natrium dari asam hialuronat yang digunakan sebagai humektan.',
            ],
            [
                'name' => 'Cocamidopropyl Betaine',
                'inci_name' => 'Cocamidopropyl Betaine',
                'safety_rating' => 4,
                'description' => 'Surfaktan yang membantu membersihkan dan menghasilkan busa pada produk pembersih.',
            ],
        ];

        // [
        //     'name' => [
        //         'en' => '',
        //         'id' => '',
        //     ],
        //     'inci_name' => 'Cocamidopropyl Betaine',
        //     'safety_rating' => 4,
        //     'description' => [
        //         'en' => '',
        //         'id' => ''
        //     ],
        // ],

        $now = now();

        foreach ($ingredients as &$ingredient) {
            $ingredient['created_at'] = $now;
            $ingredient['updated_at'] = $now;
        }

        unset($ingredient);

        DB::table('ingredients')->upsert(
            $ingredients,
            ['name'],
            ['inci_name', 'safety_rating', 'description', 'updated_at']
        );
    }
}
