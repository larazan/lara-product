<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        /*
         * Format:
         * [brand_name, brand_slug, company_slug, website]
         */
        $brands = [
            // Apple
            ['Apple', 'apple', 'apple-inc', 'https://www.apple.com'],

            // Samsung
            ['Samsung', 'samsung', 'samsung-electronics', 'https://www.samsung.com'],

            // Xiaomi family
            ['Xiaomi', 'xiaomi', 'xiaomi-corporation', 'https://www.mi.com'],
            ['Redmi', 'redmi', 'xiaomi-corporation', 'https://www.mi.com'],
            ['POCO', 'poco', 'xiaomi-corporation', 'https://www.po.co'],

            // Other smartphone brands
            ['OPPO', 'oppo', 'oppo-company', 'https://www.oppo.com'],
            ['OnePlus', 'oneplus', 'oneplus-company', 'https://www.oneplus.com'],
            ['realme', 'realme', 'realme-company', 'https://www.realme.com'],
            ['Google', 'google', 'google-llc', 'https://store.google.com'],
            ['Huawei', 'huawei', 'huawei-company', 'https://consumer.huawei'],
            ['Motorola', 'motorola', 'motorola-mobility', 'https://www.motorola.com'],
            ['Sony', 'sony', 'sony-group', 'https://www.sony.com'],

            // Computers
            ['ASUS', 'asus', 'asus-company', 'https://www.asus.com'],
            ['Dell', 'dell', 'dell-technologies', 'https://www.dell.com'],
            ['HP', 'hp', 'hp-inc', 'https://www.hp.com'],
            ['Lenovo', 'lenovo', 'lenovo-company', 'https://www.lenovo.com'],
            ['Acer', 'acer', 'acer-company', 'https://www.acer.com'],
            ['MSI', 'msi', 'msi-company', 'https://www.msi.com'],
            ['Microsoft', 'microsoft', 'microsoft-corporation', 'https://www.microsoft.com'],

            // Computer components
            ['Intel', 'intel', 'intel-corporation', 'https://www.intel.com'],
            ['AMD', 'amd', 'amd-company', 'https://www.amd.com'],
            ['NVIDIA', 'nvidia', 'nvidia-corporation', 'https://www.nvidia.com'],

            // TVs and home entertainment
            ['LG', 'lg', 'lg-electronics', 'https://www.lg.com'],
            ['TCL', 'tcl', 'tcl-company', 'https://www.tcl.com'],
            ['Logitech', 'logitech', 'logitech-company', 'https://www.logitech.com'],
            ['Bose', 'bose', 'bose-company', 'https://www.bose.com'],

            // Cameras
            ['Canon', 'canon', 'canon-company', 'https://global.canon'],
            ['Nikon', 'nikon', 'nikon-company', 'https://www.nikon.com'],
            ['DJI', 'dji', 'dji-company', 'https://www.dji.com'],

            // Wearables
            ['Garmin', 'garmin', 'garmin-company', 'https://www.garmin.com'],

            // Home appliances
            ['Electrolux', 'electrolux', 'electrolux-company', 'https://www.electrolux.com'],
            ['Midea', 'midea', 'midea-company', 'https://www.midea.com'],

            // Automotive
            ['Toyota', 'toyota', 'toyota-motor', 'https://www.toyota.com'],
            ['Lexus', 'lexus', 'toyota-motor', 'https://www.lexus.com'],
            ['Honda', 'honda', 'honda-motor', 'https://www.honda.com'],
            ['Acura', 'acura', 'honda-motor', 'https://www.acura.com'],
            ['Hyundai', 'hyundai', 'hyundai-motor', 'https://www.hyundai.com'],
            ['BMW', 'bmw', 'bmw-group', 'https://www.bmw.com'],
            ['MINI', 'mini', 'bmw-group', 'https://www.mini.com'],
            ['Mercedes-Benz', 'mercedes-benz', 'mercedes-benz-group', 'https://www.mercedes-benz.com'],
            ['BYD', 'byd', 'byd-company', 'https://www.byd.com'],
            ['Wuling', 'wuling', 'wuling-company', 'https://wuling.id'],
        ];

        DB::transaction(function () use ($brands) {
            foreach ($brands as [$name, $slug, $companySlug, $website]) {
                $company = Company::query()
                    ->where('slug', $companySlug)
                    ->first();

                if (! $company) {
                    $this->command?->warn(
                        "Company '{$companySlug}' tidak ditemukan. "
                            . "Brand '{$name}' dilewati."
                    );

                    continue;
                }

                $brand = Brand::withTrashed()
                    ->firstOrNew(['slug' => $slug]);

                $brand->fill([
                    'company_id' => $company->id,
                    'name' => $name,
                    'website' => $website,
                    'is_active' => true,
                ]);

                $brand->save();

                if ($brand->trashed()) {
                    $brand->restore();
                }
            }
        });

        $this->command?->info('BrandSeeder berhasil dijalankan.');
    }
}
