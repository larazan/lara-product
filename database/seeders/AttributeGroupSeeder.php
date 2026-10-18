<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\AttributeGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // $taxonomy = [
        //     'smartphones-unlocked-cell-phones' => [
        //         ['Design', 'Desain'],
        //         ['Display', 'Layar'],
        //         ['Performance', 'Performa'],
        //         ['Memory', 'Memori'],
        //         ['Cameras', 'Kamera'],
        //         ['Battery', 'Baterai'],
        //         ['Audio', 'Audio'],
        //         ['Connectivity & Features', 'Konektivitas & Fitur'],
        //         ['Software', 'Perangkat Lunak'],
        //     ],
        //     'laptops-notebooks-gaming-ultrabooks-chromebooks' => [
        //         ['Design & Mobility', 'Desain & Mobilitas'],
        //         ['Display', 'Layar'],
        //         ['Processor', 'Prosesor'],
        //         ['Graphics', 'Grafis'],
        //         ['Memory & Storage', 'Memori & Penyimpanan'],
        //         ['Battery', 'Baterai'],
        //         ['Connectivity & Ports', 'Konektivitas & Port'],
        //         ['Features', 'Fitur'],
        //     ],
        //     'desktop-computers-towers-all-in-ones-mini-pcs' => [
        //         ['Processor', 'Prosesor'],
        //         ['Graphics', 'Grafis'],
        //         ['Memory & Storage', 'Memori & Penyimpanan'],
        //         ['Connectivity', 'Konektivitas'],
        //         ['Power & Cooling', 'Daya & Pendinginan'],
        //         ['Dimensions & Design', 'Dimensi & Desain'],
        //     ],
        //     'computer-components-cpus-gpus-motherboards-ram-storage' => [
        //         ['Core Specifications', 'Spesifikasi Inti'],
        //         ['Performance', 'Performa'],
        //         ['Power & Thermals', 'Daya & Termal'],
        //         ['Connectivity & Compatibility', 'Konektivitas & Kompatibilitas'],
        //     ],
        //     'monitors-accessories' => [
        //         ['Display', 'Layar'],
        //         ['Performance', 'Performa'],
        //         ['Connectivity', 'Konektivitas'],
        //         ['Ergonomics & Design', 'Ergonomi & Desain'],
        //         ['Power & Features', 'Daya & Fitur'],
        //     ],
        //     'headphones-earbuds-over-ear-in-ear-true-wireless-noise-canceling' => [
        //         ['Design & Comfort', 'Desain & Kenyamanan'],
        //         ['Sound Quality', 'Kualitas Suara'],
        //         ['Noise Cancellation', 'Peredam Bising'],
        //         ['Battery', 'Baterai'],
        //         ['Connectivity', 'Konektivitas'],
        //         ['Microphone & Controls', 'Mikrofon & Kontrol'],
        //         ['Features', 'Fitur'],
        //     ],
        //     'bluetooth-portable-speakers' => [
        //         ['Sound Quality', 'Kualitas Suara'],
        //         ['Power & Volume', 'Daya & Volume'],
        //         ['Battery', 'Baterai'],
        //         ['Connectivity', 'Konektivitas'],
        //         ['Durability & Design', 'Ketahanan & Desain'],
        //     ],
        //     'televisions-oled-qled-4k-smart-tvs' => [
        //         ['Display', 'Layar'],
        //         ['Picture Quality', 'Kualitas Gambar'],
        //         ['Audio', 'Audio'],
        //         ['Smart Features', 'Fitur Pintar'],
        //         ['Connectivity', 'Konektivitas'],
        //         ['Power & Dimensions', 'Daya & Dimensi'],
        //     ],
        //     'digital-cameras-dslr-mirrorless-compact' => [
        //         ['Image Quality', 'Kualitas Gambar'],
        //         ['Sensor & Optics', 'Sensor & Optik'],
        //         ['Photography Features', 'Fitur Fotografi'],
        //         ['Video', 'Video'],
        //         ['Autofocus & Speed', 'Autofokus & Kecepatan'],
        //         ['Connectivity', 'Konektivitas'],
        //         ['Design & Battery', 'Desain & Baterai'],
        //     ],
        //     'action-cameras-camcorders' => [
        //         ['Video & Image Quality', 'Kualitas Video & Gambar'],
        //         ['Stabilization', 'Stabilisasi'],
        //         ['Durability', 'Ketahanan'],
        //         ['Battery & Storage', 'Baterai & Penyimpanan'],
        //         ['Connectivity & Features', 'Konektivitas & Fitur'],
        //     ],
        //     'smartwatches-fitness-trackers' => [
        //         ['Design & Display', 'Desain & Layar'],
        //         ['Health & Fitness', 'Kesehatan & Kebugaran'],
        //         ['Battery', 'Baterai'],
        //         ['Connectivity', 'Konektivitas'],
        //         ['Software & Compatibility', 'Perangkat Lunak & Kompatibilitas'],
        //         ['Sensors & Features', 'Sensor & Fitur'],
        //     ],
        //     'small-appliances-air-fryers-blenders-coffee-makers-microwave' => [
        //         ['Capacity & Design', 'Kapasitas & Desain'],
        //         ['Performance', 'Performa'],
        //         ['Programs & Features', 'Program & Fitur'],
        //         ['Power & Efficiency', 'Daya & Efisiensi'],
        //     ],
        //     'refrigerators-freezers' => [
        //         ['Capacity & Dimensions', 'Kapasitas & Dimensi'],
        //         ['Cooling Performance', 'Performa Pendinginan'],
        //         ['Energy Efficiency', 'Efisiensi Energi'],
        //         ['Storage & Features', 'Penyimpanan & Fitur'],
        //     ],
        //     'washing-machines-front-load-top-load' => [
        //         ['Capacity & Dimensions', 'Kapasitas & Dimensi'],
        //         ['Washing Performance', 'Performa Pencucian'],
        //         ['Water & Energy Efficiency', 'Efisiensi Air & Energi'],
        //         ['Programs & Features', 'Program & Fitur'],
        //     ],
        //     'car-wash-wax-polishes' => [
        //         ['Product Details', 'Detail Produk'],
        //         ['Cleaning Performance', 'Performa Pembersihan'],
        //         ['Compatibility & Usage', 'Kompatibilitas & Penggunaan'],
        //     ],
        //     'brake-pads-rotors' => [
        //         ['Compatibility', 'Kompatibilitas'],
        //         ['Performance & Materials', 'Performa & Material'],
        //         ['Dimensions', 'Dimensi'],
        //     ],
        //     'car-audio-head-units-speakers' => [
        //         ['Audio Performance', 'Performa Audio'],
        //         ['Connectivity & Compatibility', 'Konektivitas & Kompatibilitas'],
        //         ['Display & Controls', 'Layar & Kontrol'],
        //         ['Features', 'Fitur'],
        //     ],
        //     'dash-cams-gps-navigation' => [
        //         ['Recording & Navigation', 'Perekaman & Navigasi'],
        //         ['Display & Sensors', 'Layar & Sensor'],
        //         ['Storage & Connectivity', 'Penyimpanan & Konektivitas'],
        //         ['Safety & Features', 'Keselamatan & Fitur'],
        //     ],
        // ];

        // foreach ($taxonomy as $categorySlug => $groups) {
        //     $category = Category::where('slug', $categorySlug)->first();

        //     if (! $category) {
        //         $this->command?->warn("Kategori tidak ditemukan: {$categorySlug}");
        //         continue;
        //     }

        //     foreach ($groups as $index => [$nameEn, $nameId]) {
        //         // Match by English translation to make this seeder repeatable.
        //         $group = AttributeGroup::where('category_id', $category->id)
        //             ->get()
        //             ->first(
        //                 fn(AttributeGroup $item) => ($item->getTranslations('name')['en'] ?? null) === $nameEn
        //             );

        //         if (! $group) {
        //             $group = new AttributeGroup();
        //             $group->category_id = $category->id;
        //         }

        //         $group->name = ['en' => $nameEn, 'id' => $nameId];
        //         $group->label = ['en' => $nameEn, 'id' => $nameId];
        //         $group->order = $index + 1;
        //         $group->icon = $this->iconFor($nameEn);
        //         $group->score_weight = 1;
        //         $group->save();
        //     }
        // }

        $groupsByCategory = [
            // Electronics & Computers
            'smartphones-unlocked-cell-phones' => [
                ['en' => 'Design', 'id' => 'Desain', 'weight' => 1],
                ['en' => 'Display', 'id' => 'Layar', 'weight' => 1],
                ['en' => 'Performance', 'id' => 'Performa', 'weight' => 1],
                ['en' => 'Memory', 'id' => 'Memori', 'weight' => 1],
                ['en' => 'Cameras', 'id' => 'Kamera', 'weight' => 1],
                ['en' => 'Battery', 'id' => 'Baterai', 'weight' => 1],
                ['en' => 'Audio', 'id' => 'Audio', 'weight' => 1],
                ['en' => 'Connectivity & Features', 'id' => 'Konektivitas & Fitur', 'weight' => 1],
                ['en' => 'Software', 'id' => 'Perangkat Lunak', 'weight' => 1],
            ],
            'laptops-notebooks-gaming-ultrabooks-chromebooks' => [
                ['en' => 'Design & Mobility', 'id' => 'Desain & Mobilitas', 'weight' => 1],
                ['en' => 'Display', 'id' => 'Layar', 'weight' => 1],
                ['en' => 'Processor', 'id' => 'Prosesor', 'weight' => 1],
                ['en' => 'Graphics', 'id' => 'Grafis', 'weight' => 1],
                ['en' => 'Memory & Storage', 'id' => 'Memori & Penyimpanan', 'weight' => 1],
                ['en' => 'Battery', 'id' => 'Baterai', 'weight' => 1],
                ['en' => 'Connectivity & Ports', 'id' => 'Konektivitas & Port', 'weight' => 1],
                ['en' => 'Features', 'id' => 'Fitur', 'weight' => 1],
            ],
            'desktop-computers-towers-all-in-ones-mini-pcs' => [
                ['en' => 'Processor', 'id' => 'Prosesor', 'weight' => 1],
                ['en' => 'Graphics', 'id' => 'Grafis', 'weight' => 1],
                ['en' => 'Memory & Storage', 'id' => 'Memori & Penyimpanan', 'weight' => 1],
                ['en' => 'Connectivity', 'id' => 'Konektivitas', 'weight' => 1],
                ['en' => 'Power & Cooling', 'id' => 'Daya & Pendinginan', 'weight' => 1],
                ['en' => 'Dimensions & Design', 'id' => 'Dimensi & Desain', 'weight' => 1],
            ],
            'computer-components-cpus-gpus-motherboards-ram-storage' => [
                ['en' => 'Core Specifications', 'id' => 'Spesifikasi Inti', 'weight' => 1],
                ['en' => 'Performance', 'id' => 'Performa', 'weight' => 1],
                ['en' => 'Power & Thermals', 'id' => 'Daya & Termal', 'weight' => 1],
                ['en' => 'Connectivity & Compatibility', 'id' => 'Konektivitas & Kompatibilitas', 'weight' => 1],
            ],
            'monitors-accessories' => [
                ['en' => 'Display', 'id' => 'Layar', 'weight' => 1],
                ['en' => 'Performance', 'id' => 'Performa', 'weight' => 1],
                ['en' => 'Connectivity', 'id' => 'Konektivitas', 'weight' => 1],
                ['en' => 'Ergonomics & Design', 'id' => 'Ergonomi & Desain', 'weight' => 1],
                ['en' => 'Power & Features', 'id' => 'Daya & Fitur', 'weight' => 1],
            ],
            'headphones-earbuds-over-ear-in-ear-true-wireless-noise-canceling' => [
                ['en' => 'Design & Comfort', 'id' => 'Desain & Kenyamanan', 'weight' => 1],
                ['en' => 'Sound Quality', 'id' => 'Kualitas Suara', 'weight' => 1],
                ['en' => 'Noise Cancellation', 'id' => 'Peredam Bising', 'weight' => 1],
                ['en' => 'Battery', 'id' => 'Baterai', 'weight' => 1],
                ['en' => 'Connectivity', 'id' => 'Konektivitas', 'weight' => 1],
                ['en' => 'Microphone & Controls', 'id' => 'Mikrofon & Kontrol', 'weight' => 1],
                ['en' => 'Features', 'id' => 'Fitur', 'weight' => 1],
            ],
            'bluetooth-portable-speakers' => [
                ['en' => 'Sound Quality', 'id' => 'Kualitas Suara', 'weight' => 1],
                ['en' => 'Power & Volume', 'id' => 'Daya & Volume', 'weight' => 1],
                ['en' => 'Battery', 'id' => 'Baterai', 'weight' => 1],
                ['en' => 'Connectivity', 'id' => 'Konektivitas', 'weight' => 1],
                ['en' => 'Durability & Design', 'id' => 'Ketahanan & Desain', 'weight' => 1],
            ],
            'televisions-oled-qled-4k-smart-tvs' => [
                ['en' => 'Display', 'id' => 'Layar', 'weight' => 1],
                ['en' => 'Picture Quality', 'id' => 'Kualitas Gambar', 'weight' => 1],
                ['en' => 'Audio', 'id' => 'Audio', 'weight' => 1],
                ['en' => 'Smart Features', 'id' => 'Fitur Pintar', 'weight' => 1],
                ['en' => 'Connectivity', 'id' => 'Konektivitas', 'weight' => 1],
                ['en' => 'Power & Dimensions', 'id' => 'Daya & Dimensi', 'weight' => 1],
            ],
            'digital-cameras-dslr-mirrorless-compact' => [
                ['en' => 'Image Quality', 'id' => 'Kualitas Gambar', 'weight' => 1],
                ['en' => 'Sensor & Optics', 'id' => 'Sensor & Optik', 'weight' => 1],
                ['en' => 'Photography Features', 'id' => 'Fitur Fotografi', 'weight' => 1],
                ['en' => 'Video', 'id' => 'Video', 'weight' => 1],
                ['en' => 'Autofocus & Speed', 'id' => 'Autofokus & Kecepatan', 'weight' => 1],
                ['en' => 'Connectivity', 'id' => 'Konektivitas', 'weight' => 1],
                ['en' => 'Design & Battery', 'id' => 'Desain & Baterai', 'weight' => 1],
            ],
            'action-cameras-camcorders' => [
                ['en' => 'Video & Image Quality', 'id' => 'Kualitas Video & Gambar', 'weight' => 1],
                ['en' => 'Stabilization', 'id' => 'Stabilisasi', 'weight' => 1],
                ['en' => 'Durability', 'id' => 'Ketahanan', 'weight' => 1],
                ['en' => 'Battery & Storage', 'id' => 'Baterai & Penyimpanan', 'weight' => 1],
                ['en' => 'Connectivity & Features', 'id' => 'Konektivitas & Fitur', 'weight' => 1],
            ],
            'smartwatches-fitness-trackers' => [
                ['en' => 'Design & Display', 'id' => 'Desain & Layar', 'weight' => 1],
                ['en' => 'Health & Fitness', 'id' => 'Kesehatan & Kebugaran', 'weight' => 1],
                ['en' => 'Battery', 'id' => 'Baterai', 'weight' => 1],
                ['en' => 'Connectivity', 'id' => 'Konektivitas', 'weight' => 1],
                ['en' => 'Software & Compatibility', 'id' => 'Perangkat Lunak & Kompatibilitas', 'weight' => 1],
                ['en' => 'Sensors & Features', 'id' => 'Sensor & Fitur', 'weight' => 1],
            ],

            // Home & Kitchen / Major Appliances
            'small-appliances-air-fryers-blenders-coffee-makers-microwave' => [
                ['en' => 'Capacity & Design', 'id' => 'Kapasitas & Desain', 'weight' => 1],
                ['en' => 'Performance', 'id' => 'Performa', 'weight' => 1],
                ['en' => 'Programs & Features', 'id' => 'Program & Fitur', 'weight' => 1],
                ['en' => 'Power & Efficiency', 'id' => 'Daya & Efisiensi', 'weight' => 1],
            ],
            'refrigerators-freezers' => [
                ['en' => 'Capacity & Dimensions', 'id' => 'Kapasitas & Dimensi', 'weight' => 1],
                ['en' => 'Cooling Performance', 'id' => 'Performa Pendinginan', 'weight' => 1],
                ['en' => 'Energy Efficiency', 'id' => 'Efisiensi Energi', 'weight' => 1],
                ['en' => 'Storage & Features', 'id' => 'Penyimpanan & Fitur', 'weight' => 1],
            ],
            'washing-machines-front-load-top-load' => [
                ['en' => 'Capacity & Dimensions', 'id' => 'Kapasitas & Dimensi', 'weight' => 1],
                ['en' => 'Washing Performance', 'id' => 'Performa Pencucian', 'weight' => 1],
                ['en' => 'Water & Energy Efficiency', 'id' => 'Efisiensi Air & Energi', 'weight' => 1],
                ['en' => 'Programs & Features', 'id' => 'Program & Fitur', 'weight' => 1],
            ],

            // Automotive
            'car-wash-wax-polishes' => [
                ['en' => 'Product Details', 'id' => 'Detail Produk', 'weight' => 1],
                ['en' => 'Cleaning Performance', 'id' => 'Performa Pembersihan', 'weight' => 1],
                ['en' => 'Compatibility & Usage', 'id' => 'Kompatibilitas & Penggunaan', 'weight' => 1],
            ],
            'brake-pads-rotors' => [
                ['en' => 'Compatibility', 'id' => 'Kompatibilitas', 'weight' => 1],
                ['en' => 'Performance & Materials', 'id' => 'Performa & Material', 'weight' => 1],
                ['en' => 'Dimensions', 'id' => 'Dimensi', 'weight' => 1],
            ],
            'car-audio-head-units-speakers' => [
                ['en' => 'Audio Performance', 'id' => 'Performa Audio', 'weight' => 1],
                ['en' => 'Connectivity & Compatibility', 'id' => 'Konektivitas & Kompatibilitas', 'weight' => 1],
                ['en' => 'Display & Controls', 'id' => 'Layar & Kontrol', 'weight' => 1],
                ['en' => 'Features', 'id' => 'Fitur', 'weight' => 1],
            ],
            'dash-cams-gps-navigation' => [
                ['en' => 'Recording & Navigation', 'id' => 'Perekaman & Navigasi', 'weight' => 1],
                ['en' => 'Display & Sensors', 'id' => 'Layar & Sensor', 'weight' => 1],
                ['en' => 'Storage & Connectivity', 'id' => 'Penyimpanan & Konektivitas', 'weight' => 1],
                ['en' => 'Safety & Features', 'id' => 'Keselamatan & Fitur', 'weight' => 1],
            ],
        ];

        $missingCategories = [];

        foreach ($groupsByCategory as $categorySlug => $groups) {
            $category = Category::where('slug', $categorySlug)->first();

            if (! $category) {
                $missingCategories[] = $categorySlug;
                continue;
            }

            foreach ($groups as $index => $group) {
                $name = [
                    'en' => $group['en'],
                    'id' => $group['id'],
                ];

                // Idempotent without requiring a unique index on a JSON column.
                $attributeGroup = AttributeGroup::query()
                    ->where('category_id', $category->id)
                    ->get()
                    ->first(function (AttributeGroup $existing) use ($group) {
                        $existingName = $existing->getTranslations('name');

                        return ($existingName['en'] ?? null) === $group['en'];
                    });

                if (! $attributeGroup) {
                    $attributeGroup = new AttributeGroup();
                    $attributeGroup->category_id = $category->id;
                    $attributeGroup->name = $name;
                } else {
                    $attributeGroup->name = $name;
                }

                $attributeGroup->label = $name;
                $attributeGroup->order = $index + 1;
                $attributeGroup->icon = $this->iconFor($group['en']);
                $attributeGroup->score_weight = $group['weight'] ?? 1;
                $attributeGroup->save();
            }
        }

        if ($missingCategories) {
            $this->command?->warn(
                'Kategori tidak ditemukan (slug harus cocok dengan CategorySeeder): '
                    . implode(', ', $missingCategories)
            );
        }
    }

    private function iconFor(string $name): string
    {
        return match ($name) {
            'Design', 'Design & Mobility', 'Design & Comfort', 'Design & Display',
            'Ergonomics & Design', 'Dimensions & Design' => 'smartphone',
            'Display', 'Display & Controls', 'Display & Sensors' => 'monitor',
            'Performance', 'Washing Performance', 'Cooling Performance', 'Audio Performance',
            'Cleaning Performance' => 'gauge',
            'Memory', 'Memory & Storage', 'Battery & Storage', 'Storage & Features' => 'database',
            'Cameras', 'Image Quality', 'Video & Image Quality', 'Recording & Navigation' => 'camera',
            'Battery', 'Design & Battery' => 'battery-charging',
            'Audio', 'Sound Quality' => 'volume-2',
            'Connectivity', 'Connectivity & Features', 'Connectivity & Ports',
            'Connectivity & Compatibility', 'Storage & Connectivity' => 'wifi',
            'Software', 'Software & Compatibility', 'Smart Features' => 'settings',
            'Processor', 'Core Specifications' => 'cpu',
            'Graphics' => 'gpu',
            'Noise Cancellation' => 'audio-lines',
            'Health & Fitness', 'Sensors & Features' => 'activity',
            'Capacity & Dimensions', 'Dimensions' => 'ruler',
            'Energy Efficiency', 'Water & Energy Efficiency', 'Power & Efficiency',
            'Power & Cooling' => 'zap',
            'Safety & Features', 'Compatibility', 'Compatibility & Usage' => 'shield-check',
            default => 'list',
        };
    }
}
