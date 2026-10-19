<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $companies = [
            [
                'name' => 'Apple',
                'slug' => 'apple-inc',
                'legal_name' => 'Apple Inc.',
                'website' => 'https://www.apple.com',
            ],
            [
                'name' => 'Samsung Electronics',
                'slug' => 'samsung-electronics',
                'legal_name' => 'Samsung Electronics Co., Ltd.',
                'website' => 'https://www.samsung.com',
            ],
            [
                'name' => 'Xiaomi',
                'slug' => 'xiaomi-corporation',
                'legal_name' => 'Xiaomi Corporation',
                'website' => 'https://www.mi.com',
            ],
            [
                'name' => 'OPPO',
                'slug' => 'oppo-company',
                'legal_name' => 'Guangdong OPPO Mobile Telecommunications Corp., Ltd.',
                'website' => 'https://www.oppo.com',
            ],
            [
                'name' => 'OnePlus',
                'slug' => 'oneplus-company',
                'legal_name' => 'OnePlus Technology',
                'website' => 'https://www.oneplus.com',
            ],
            [
                'name' => 'realme',
                'slug' => 'realme-company',
                'legal_name' => 'realme',
                'website' => 'https://www.realme.com',
            ],
            [
                'name' => 'Google',
                'slug' => 'google-llc',
                'legal_name' => 'Google LLC',
                'website' => 'https://www.google.com',
            ],
            [
                'name' => 'Huawei',
                'slug' => 'huawei-company',
                'legal_name' => 'Huawei Technologies Co., Ltd.',
                'website' => 'https://www.huawei.com',
            ],
            [
                'name' => 'Motorola Mobility',
                'slug' => 'motorola-mobility',
                'legal_name' => 'Motorola Mobility LLC',
                'website' => 'https://www.motorola.com',
            ],
            [
                'name' => 'Sony Group',
                'slug' => 'sony-group',
                'legal_name' => 'Sony Group Corporation',
                'website' => 'https://www.sony.com',
            ],
            [
                'name' => 'ASUSTeK Computer',
                'slug' => 'asus-company',
                'legal_name' => 'ASUSTeK Computer Inc.',
                'website' => 'https://www.asus.com',
            ],
            [
                'name' => 'Dell Technologies',
                'slug' => 'dell-technologies',
                'legal_name' => 'Dell Technologies Inc.',
                'website' => 'https://www.dell.com',
            ],
            [
                'name' => 'HP Inc.',
                'slug' => 'hp-inc',
                'legal_name' => 'HP Inc.',
                'website' => 'https://www.hp.com',
            ],
            [
                'name' => 'Lenovo',
                'slug' => 'lenovo-company',
                'legal_name' => 'Lenovo Group Limited',
                'website' => 'https://www.lenovo.com',
            ],
            [
                'name' => 'Acer',
                'slug' => 'acer-company',
                'legal_name' => 'Acer Incorporated',
                'website' => 'https://www.acer.com',
            ],
            [
                'name' => 'Micro-Star International',
                'slug' => 'msi-company',
                'legal_name' => 'Micro-Star International Co., Ltd.',
                'website' => 'https://www.msi.com',
            ],
            [
                'name' => 'Microsoft',
                'slug' => 'microsoft-corporation',
                'legal_name' => 'Microsoft Corporation',
                'website' => 'https://www.microsoft.com',
            ],
            [
                'name' => 'Intel',
                'slug' => 'intel-corporation',
                'legal_name' => 'Intel Corporation',
                'website' => 'https://www.intel.com',
            ],
            [
                'name' => 'AMD',
                'slug' => 'amd-company',
                'legal_name' => 'Advanced Micro Devices, Inc.',
                'website' => 'https://www.amd.com',
            ],
            [
                'name' => 'NVIDIA',
                'slug' => 'nvidia-corporation',
                'legal_name' => 'NVIDIA Corporation',
                'website' => 'https://www.nvidia.com',
            ],
            [
                'name' => 'LG Electronics',
                'slug' => 'lg-electronics',
                'legal_name' => 'LG Electronics Inc.',
                'website' => 'https://www.lg.com',
            ],
            [
                'name' => 'TCL',
                'slug' => 'tcl-company',
                'legal_name' => 'TCL',
                'website' => 'https://www.tcl.com',
            ],
            [
                'name' => 'Canon',
                'slug' => 'canon-company',
                'legal_name' => 'Canon Inc.',
                'website' => 'https://global.canon',
            ],
            [
                'name' => 'Nikon',
                'slug' => 'nikon-company',
                'legal_name' => 'Nikon Corporation',
                'website' => 'https://www.nikon.com',
            ],
            [
                'name' => 'DJI',
                'slug' => 'dji-company',
                'legal_name' => 'SZ DJI Technology Co., Ltd.',
                'website' => 'https://www.dji.com',
            ],
            [
                'name' => 'Garmin',
                'slug' => 'garmin-company',
                'legal_name' => 'Garmin Ltd.',
                'website' => 'https://www.garmin.com',
            ],
            [
                'name' => 'Bose',
                'slug' => 'bose-company',
                'legal_name' => 'Bose Corporation',
                'website' => 'https://www.bose.com',
            ],
            [
                'name' => 'Logitech',
                'slug' => 'logitech-company',
                'legal_name' => 'Logitech International S.A.',
                'website' => 'https://www.logitech.com',
            ],
            [
                'name' => 'Electrolux',
                'slug' => 'electrolux-company',
                'legal_name' => 'AB Electrolux',
                'website' => 'https://www.electrolux.com',
            ],
            [
                'name' => 'Midea',
                'slug' => 'midea-company',
                'legal_name' => 'Midea Group',
                'website' => 'https://www.midea.com',
            ],
            [
                'name' => 'Toyota',
                'slug' => 'toyota-motor',
                'legal_name' => 'Toyota Motor Corporation',
                'website' => 'https://global.toyota',
            ],
            [
                'name' => 'Honda',
                'slug' => 'honda-motor',
                'legal_name' => 'Honda Motor Co., Ltd.',
                'website' => 'https://global.honda',
            ],
            [
                'name' => 'Hyundai Motor',
                'slug' => 'hyundai-motor',
                'legal_name' => 'Hyundai Motor Company',
                'website' => 'https://www.hyundai.com',
            ],
            [
                'name' => 'BMW Group',
                'slug' => 'bmw-group',
                'legal_name' => 'Bayerische Motoren Werke AG',
                'website' => 'https://www.bmwgroup.com',
            ],
            [
                'name' => 'Mercedes-Benz Group',
                'slug' => 'mercedes-benz-group',
                'legal_name' => 'Mercedes-Benz Group AG',
                'website' => 'https://group.mercedes-benz.com',
            ],
            [
                'name' => 'BYD',
                'slug' => 'byd-company',
                'legal_name' => 'BYD Company Limited',
                'website' => 'https://www.byd.com',
            ],
            [
                'name' => 'Wuling',
                'slug' => 'wuling-company',
                'legal_name' => 'SAIC-GM-Wuling Automobile',
                'website' => 'https://wuling.id',
            ],
        ];

        DB::transaction(function () use ($companies) {
            foreach ($companies as $data) {
                $company = Company::withTrashed()
                    ->firstOrNew(['slug' => $data['slug']]);

                $company->fill([
                    'name' => $data['name'],
                    'legal_name' => $data['legal_name'],
                    'website' => $data['website'],
                    'is_active' => true,
                ]);

                $company->save();

                if ($company->trashed()) {
                    $company->restore();
                }
            }
        });

        $this->command?->info(
            count($companies) . ' companies berhasil diproses.'
        );
    }
}
