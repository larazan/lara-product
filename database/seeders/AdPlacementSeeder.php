<?php

namespace Database\Seeders;

use App\Models\AdPlacement;
use App\Models\AdPlacementRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdPlacementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $placements = [
            'banner' => [
                'name' => 'Banner',
                'rate' => 1,
            ],
            'product_list_top' => [
                'name' => 'Listing Posisi Pertama',
                'rate' => 2,
            ],
            'modal' => [
                'name' => 'Modal',
                'rate' => 3,
            ],
            'comparison_slot_3' => [
                'name' => 'Compare Slot Ketiga',
                'rate' => 4,
            ],
        ];

        DB::transaction(function () use ($placements) {
            foreach ($placements as $code => $data) {
                $placement = AdPlacement::updateOrCreate(
                    ['code' => $code],
                    [
                        'name' => $data['name'],
                        'is_active' => true,
                    ]
                );

                AdPlacementRate::updateOrCreate(
                    [
                        'placement_id' => $placement->id,
                        'category_id' => null,
                        'starts_at' => null,
                        'ends_at' => null,
                    ],
                    [
                        'tokens_per_1000_impressions' => $data['rate'],
                        'is_active' => true,
                    ]
                );
            }
        });
    }
}
