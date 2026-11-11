<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\LabelGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlobalLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::transaction(function () {

            $groups = [

                // -------------------------------------------------
                // 1. MARKETING
                // -------------------------------------------------
                [
                    'name' => [
                        'en' => 'Marketing',
                        'id' => 'Pemasaran',
                    ],
                    'slug' => 'marketing',
                    'description' => [
                        'en' => 'Product claims and marketing characteristics.',
                        'id' => 'Klaim produk dan karakteristik pemasaran.',
                    ],
                    'sort_order' => 1,
                    'labels' => [
                        ['organic', 'Organic', 'Organik', 'Product claims to be organically produced.', 'marketing'],
                        ['vegan', 'Vegan', 'Vegan', 'Product claims to contain no animal-derived ingredients.', 'marketing'],
                        ['vegetarian', 'Vegetarian', 'Vegetarian', 'Suitable for vegetarian consumers.', 'marketing'],
                        ['cruelty-free', 'Cruelty-Free', 'Bebas Uji Coba Hewan', 'Claims that the product or its ingredients were not tested on animals.', 'marketing'],
                        ['non-gmo', 'Non-GMO', 'Non-GMO', 'Product claims not to contain genetically modified ingredients.', 'marketing'],
                        ['gluten-free', 'Gluten-Free', 'Bebas Gluten', 'Product claims to meet applicable gluten-free requirements.', 'marketing'],
                        ['lactose-free', 'Lactose-Free', 'Bebas Laktosa', 'Product claims to contain no or only permitted trace levels of lactose.', 'marketing'],
                        ['sugar-free', 'Sugar-Free', 'Bebas Gula', 'Product claims to meet applicable sugar-free requirements.', 'marketing'],
                        ['no-added-sugar', 'No Added Sugar', 'Tanpa Gula Tambahan', 'No sugars have been added during production.', 'marketing'],
                        ['preservative-free', 'Preservative-Free', 'Tanpa Pengawet', 'Product claims not to contain added preservatives.', 'marketing'],
                        ['artificial-color-free', 'Artificial Color-Free', 'Tanpa Pewarna Buatan', 'Product claims not to contain artificial coloring.', 'marketing'],
                        ['artificial-flavor-free', 'Artificial Flavor-Free', 'Tanpa Perisa Buatan', 'Product claims not to contain artificial flavoring.', 'marketing'],
                        ['hypoallergenic', 'Hypoallergenic', 'Hipoalergenik', 'Product marketed as having a reduced potential to cause allergic reactions.', 'marketing'],
                        ['dermatologically-tested', 'Dermatologically Tested', 'Teruji Dermatologis', 'Product claims to have undergone dermatological testing.', 'marketing'],
                        ['non-comedogenic', 'Non-Comedogenic', 'Non-Komedogenik', 'Product claims to be formulated to minimize pore clogging.', 'marketing'],
                        ['recyclable-packaging', 'Recyclable Packaging', 'Kemasan Dapat Didaur Ulang', 'Packaging is designed to be recyclable where facilities exist.', 'marketing'],
                        ['refillable', 'Refillable', 'Dapat Diisi Ulang', 'Product or packaging is designed for refilling.', 'marketing'],
                        ['alcohol-free', 'Alcohol-Free', 'Bebas Alkohol', 'Product claims to meet the applicable alcohol-free definition.', 'marketing'],
                        ['non-alcoholic', 'Non-Alcoholic', 'Non-Alkohol', 'Beverage classified as non-alcoholic under applicable regulations.', 'marketing'],
                        ['low-alcohol', 'Low Alcohol', 'Rendah Alkohol', 'Beverage classified as low alcohol under applicable regulations.', 'marketing'],
                    ],
                ],

                // -------------------------------------------------
                // 2. CERTIFICATION
                // -------------------------------------------------
                [
                    'name' => [
                        'en' => 'Certification',
                        'id' => 'Sertifikasi',
                    ],
                    'slug' => 'certification',
                    'description' => [
                        'en' => 'Third-party certifications and recognized standards.',
                        'id' => 'Sertifikasi pihak ketiga dan standar yang diakui.',
                    ],
                    'sort_order' => 2,
                    'labels' => [
                        ['halal-certified', 'Halal Certified', 'Bersertifikat Halal', 'Certified as halal by an authorized certification body.', 'certification'],
                        ['kosher-certified', 'Kosher Certified', 'Bersertifikat Kosher', 'Certified as kosher by an authorized organization.', 'certification'],
                        ['fair-trade-certified', 'Fair Trade Certified', 'Bersertifikat Fair Trade', 'Certified under an applicable fair trade standard.', 'certification'],
                        ['fsc-certified', 'FSC Certified', 'Bersertifikat FSC', 'Certified under Forest Stewardship Council standards.', 'certification'],
                        ['rainforest-alliance-certified', 'Rainforest Alliance Certified', 'Bersertifikat Rainforest Alliance', 'Certified under Rainforest Alliance standards.', 'certification'],
                        ['energy-star-certified', 'ENERGY STAR Certified', 'Bersertifikat ENERGY STAR', 'Certified under the ENERGY STAR program.', 'certification'],
                        ['eu-organic-certified', 'EU Organic Certified', 'Bersertifikat Organik Uni Eropa', 'Certified under the European Union organic regulation.', 'certification'],
                        ['usda-organic-certified', 'USDA Organic Certified', 'Bersertifikat USDA Organic', 'Certified under the USDA National Organic Program.', 'certification'],
                        ['iso-9001-certified', 'ISO 9001 Certified', 'Bersertifikat ISO 9001', 'Organization or manufacturing process certified under ISO 9001.', 'certification'],
                        ['iso-14001-certified', 'ISO 14001 Certified', 'Bersertifikat ISO 14001', 'Environmental management system certified under ISO 14001.', 'certification'],
                        ['iso-22000-certified', 'ISO 22000 Certified', 'Bersertifikat ISO 22000', 'Food safety management system certified under ISO 22000.', 'certification'],
                        ['ecocert-certified', 'ECOCERT Certified', 'Bersertifikat ECOCERT', 'Certified under an applicable ECOCERT standard.', 'certification'],
                        ['leaping-bunny-certified', 'Leaping Bunny Certified', 'Bersertifikat Leaping Bunny', 'Certified under the Leaping Bunny cruelty-free program.', 'certification'],
                    ],
                ],

                // -------------------------------------------------
                // 3. REGULATORY
                // -------------------------------------------------
                [
                    'name' => [
                        'en' => 'Regulatory',
                        'id' => 'Regulasi',
                    ],
                    'slug' => 'regulatory',
                    'description' => [
                        'en' => 'Product registration, legal classification and regulatory requirements.',
                        'id' => 'Registrasi produk, klasifikasi hukum dan persyaratan regulasi.',
                    ],
                    'sort_order' => 3,
                    'labels' => [
                        ['regulatory-registration-required', 'Regulatory Registration Required', 'Wajib Registrasi Regulasi', 'Product requires registration or authorization under applicable regulations.', 'regulatory'],
                        ['regulatory-registered', 'Regulatory Registered', 'Terdaftar Secara Regulasi', 'Product has an applicable regulatory registration.', 'regulatory'],
                        ['prescription-only', 'Prescription Only', 'Hanya dengan Resep', 'Medicine supplied only with a valid prescription.', 'regulatory'],
                        ['over-the-counter', 'Over-the-Counter (OTC)', 'Obat Bebas', 'Medicine authorized for non-prescription sale.', 'regulatory'],
                        ['controlled-substance', 'Controlled Substance', 'Zat yang Diawasi', 'Substance subject to special legal controls.', 'regulatory'],
                        ['narcotic', 'Narcotic', 'Narkotika', 'Classified as a narcotic under applicable national law.', 'regulatory'],
                        ['psychotropic-substance', 'Psychotropic Substance', 'Psikotropika', 'Classified as a psychotropic substance under applicable law.', 'regulatory'],
                        ['antibiotic', 'Antibiotic', 'Antibiotik', 'Product classified as an antibiotic medicine.', 'regulatory'],
                        ['tobacco-product', 'Tobacco Product', 'Produk Tembakau', 'Product classified as a tobacco product.', 'regulatory'],
                        ['nicotine-containing', 'Contains Nicotine', 'Mengandung Nikotin', 'Product contains nicotine.', 'regulatory'],
                        ['alcoholic-beverage', 'Alcoholic Beverage', 'Minuman Beralkohol', 'Product classified as an alcoholic beverage.', 'regulatory'],
                        ['age-restricted', 'Age Restricted', 'Dibatasi Usia', 'Sale or use is restricted by minimum age under applicable law.', 'regulatory'],
                        ['dangerous-goods', 'Dangerous Goods', 'Barang Berbahaya', 'Product classified as dangerous goods for transport.', 'regulatory'],
                        ['hazardous-material', 'Hazardous Material', 'Material Berbahaya', 'Product or material is subject to hazardous material requirements.', 'regulatory'],
                        ['sds-required', 'Safety Data Sheet Required', 'Wajib SDS', 'Safety Data Sheet is required under applicable regulations.', 'regulatory'],
                        ['ce-marked', 'CE Marked', 'Bertanda CE', 'Product bears CE marking where legally applicable.', 'regulatory'],
                        ['reach-compliant', 'REACH Compliant', 'Memenuhi REACH', 'Product or substance meets applicable REACH obligations.', 'regulatory'],
                        ['clp-classified', 'CLP Classified', 'Diklasifikasikan CLP', 'Substance or mixture is classified under EU CLP rules.', 'regulatory'],
                    ],
                ],

                // -------------------------------------------------
                // 4. AGE RESTRICTION
                // -------------------------------------------------
                [
                    'name' => [
                        'en' => 'Age Restriction',
                        'id' => 'Batasan Usia',
                    ],
                    'slug' => 'age-restriction',
                    'description' => [
                        'en' => 'Products with age-related sale or usage restrictions.',
                        'id' => 'Produk dengan pembatasan penjualan atau penggunaan berdasarkan usia.',
                    ],
                    'sort_order' => 4,
                    'labels' => [
                        ['adult-only', 'Adult Only', 'Khusus Dewasa', 'Intended only for adults under applicable rules.', 'safety', 'high'],
                        ['age-verification-required', 'Age Verification Required', 'Wajib Verifikasi Usia', 'Age verification is required before sale or access.', 'safety', 'high'],
                        ['not-for-minors', 'Not for Minors', 'Tidak untuk Anak di Bawah Umur', 'Not intended or legally permitted for minors.', 'safety', 'high'],
                        ['not-for-children', 'Not for Children', 'Tidak untuk Anak-anak', 'Not intended for use by children.', 'safety', 'medium'],
                        ['keep-away-from-children', 'Keep Out of Reach of Children', 'Jauhkan dari Jangkauan Anak-anak', 'Keep the product out of children’s reach.', 'safety', 'medium'],
                    ],
                ],

                // -------------------------------------------------
                // 5. HEALTH & MEDICAL
                // -------------------------------------------------
                [
                    'name' => [
                        'en' => 'Health & Medical',
                        'id' => 'Kesehatan & Medis',
                    ],
                    'slug' => 'health-medical',
                    'description' => [
                        'en' => 'Health risks, medical warnings and usage precautions.',
                        'id' => 'Risiko kesehatan, peringatan medis dan tindakan pencegahan penggunaan.',
                    ],
                    'sort_order' => 5,
                    'labels' => [
                        ['health-warning', 'Health Warning', 'Peringatan Kesehatan', 'Product carries a health-related warning.', 'safety', 'medium'],
                        ['pregnancy-warning', 'Pregnancy Warning', 'Peringatan Kehamilan', 'Special precautions may apply during pregnancy.', 'safety', 'high'],
                        ['breastfeeding-warning', 'Breastfeeding Warning', 'Peringatan Menyusui', 'Special precautions may apply during breastfeeding.', 'safety', 'medium'],
                        ['allergen-warning', 'Allergen Warning', 'Peringatan Alergen', 'Product contains or may contain specified allergens.', 'safety', 'high'],
                        ['drowsiness-warning', 'Drowsiness Warning', 'Peringatan Menyebabkan Kantuk', 'Product may cause drowsiness.', 'safety', 'medium'],
                        ['drug-interaction-warning', 'Drug Interaction Warning', 'Peringatan Interaksi Obat', 'Product may interact with other medicines or substances.', 'safety', 'high'],
                        ['misuse-risk', 'Misuse Risk', 'Risiko Penyalahgunaan', 'Product has a recognized risk of misuse.', 'safety', 'high'],
                        ['dependence-risk', 'Dependence Risk', 'Risiko Ketergantungan', 'Product may cause physical or psychological dependence.', 'safety', 'high'],
                        ['high-alert-medication', 'High-Alert Medication', 'Obat Risiko Tinggi', 'Medication has an increased risk of serious harm if used incorrectly.', 'safety', 'critical'],
                        ['black-box-warning', 'Black Box Warning', 'Peringatan Black Box', 'Medicine carries an FDA boxed warning.', 'safety', 'critical'],
                        ['contraindication-warning', 'Contraindication Warning', 'Peringatan Kontraindikasi', 'Product should not be used in specified conditions.', 'safety', 'high'],
                        ['professional-use-only', 'Professional Use Only', 'Hanya untuk Penggunaan Profesional', 'Product is intended for use by trained professionals.', 'safety', 'medium'],
                        ['external-use-only', 'External Use Only', 'Hanya untuk Pemakaian Luar', 'Product must only be applied externally.', 'safety', 'medium'],
                        ['not-for-ingestion', 'Not for Ingestion', 'Tidak untuk Ditelan', 'Product must not be swallowed or ingested.', 'safety', 'high'],
                        ['refrigeration-required', 'Refrigeration Required', 'Wajib Disimpan dalam Pendingin', 'Product requires refrigerated storage.', 'safety', 'medium'],
                    ],
                ],

                // -------------------------------------------------
                // 6. CHEMICAL HAZARDS
                // -------------------------------------------------
                [
                    'name' => [
                        'en' => 'Chemical Hazards',
                        'id' => 'Bahaya Kimia',
                    ],
                    'slug' => 'chemical-hazards',
                    'description' => [
                        'en' => 'GHS chemical hazard classifications and precautions.',
                        'id' => 'Klasifikasi bahaya bahan kimia GHS dan tindakan pencegahannya.',
                    ],
                    'sort_order' => 6,
                    'labels' => [
                        ['ghs01-explosive', 'Explosive (GHS01)', 'Mudah Meledak (GHS01)', 'Explosive or unstable explosive hazard.', 'safety', 'critical'],
                        ['ghs02-flammable', 'Flammable (GHS02)', 'Mudah Terbakar (GHS02)', 'Flammable gas, liquid, solid or other applicable hazard.', 'safety', 'high'],
                        ['ghs03-oxidizing', 'Oxidizing (GHS03)', 'Pengoksidasi (GHS03)', 'May cause or intensify fire or explosion.', 'safety', 'high'],
                        ['ghs04-gas-pressure', 'Gas Under Pressure (GHS04)', 'Gas Bertekanan (GHS04)', 'Contains gas under pressure.', 'safety', 'medium'],
                        ['ghs05-corrosive', 'Corrosive (GHS05)', 'Korosif (GHS05)', 'May cause severe skin burns or eye damage, or corrode metals.', 'safety', 'critical'],
                        ['ghs06-acute-toxicity', 'Acute Toxicity (GHS06)', 'Toksisitas Akut (GHS06)', 'May be fatal or toxic if swallowed, inhaled or in contact with skin.', 'safety', 'critical'],
                        ['ghs07-irritant', 'Irritant (GHS07)', 'Iritan (GHS07)', 'May cause irritation or other specified harmful effects.', 'safety', 'medium'],
                        ['ghs08-health-hazard', 'Serious Health Hazard (GHS08)', 'Bahaya Kesehatan Serius (GHS08)', 'May cause serious or chronic health effects.', 'safety', 'critical'],
                        ['ghs09-environmental-hazard', 'Environmental Hazard (GHS09)', 'Bahaya Lingkungan (GHS09)', 'Hazardous to the aquatic environment.', 'safety', 'high'],
                        ['chemical-burn-risk', 'Chemical Burn Risk', 'Risiko Luka Bakar Kimia', 'May cause chemical burns.', 'safety', 'high'],
                        ['toxic-substance', 'Toxic Substance', 'Zat Beracun', 'Contains a substance classified as toxic.', 'safety', 'critical'],
                        ['carcinogenic-substance', 'Carcinogenic Substance', 'Zat Karsinogenik', 'Contains a substance classified as carcinogenic.', 'safety', 'critical'],
                        ['mutagenic-substance', 'Mutagenic Substance', 'Zat Mutagenik', 'Contains a substance classified as mutagenic.', 'safety', 'critical'],
                        ['reproductive-toxicity', 'Reproductive Toxicity', 'Toksisitas Reproduksi', 'May adversely affect reproductive health.', 'safety', 'critical'],
                        ['environmentally-hazardous-chemical', 'Environmentally Hazardous Chemical', 'Bahan Kimia Berbahaya bagi Lingkungan', 'Chemical classified as hazardous to the environment.', 'safety', 'high'],
                        ['do-not-mix-with-other-chemicals', 'Do Not Mix With Other Chemicals', 'Jangan Dicampur dengan Bahan Kimia Lain', 'Mixing with certain chemicals may create hazardous reactions.', 'safety', 'high'],
                    ],
                ],

                // -------------------------------------------------
                // 7. PHYSICAL HAZARDS
                // -------------------------------------------------
                [
                    'name' => [
                        'en' => 'Physical Hazards',
                        'id' => 'Bahaya Fisik',
                    ],
                    'slug' => 'physical-hazards',
                    'description' => [
                        'en' => 'Fire, heat, pressure and physical product hazards.',
                        'id' => 'Bahaya kebakaran, panas, tekanan dan bahaya fisik produk.',
                    ],
                    'sort_order' => 7,
                    'labels' => [
                        ['flammable-liquid', 'Flammable Liquid', 'Cairan Mudah Terbakar', 'Contains a flammable liquid.', 'safety', 'high'],
                        ['flammable-gas', 'Flammable Gas', 'Gas Mudah Terbakar', 'Contains a flammable gas.', 'safety', 'high'],
                        ['pressurized-container', 'Pressurized Container', 'Wadah Bertekanan', 'Container is pressurized and may burst if heated.', 'safety', 'high'],
                        ['explosion-risk', 'Explosion Risk', 'Risiko Ledakan', 'Product may explode under specified conditions.', 'safety', 'critical'],
                        ['fire-hazard', 'Fire Hazard', 'Bahaya Kebakaran', 'Product presents a fire hazard.', 'safety', 'high'],
                        ['hot-surface-warning', 'Hot Surface Warning', 'Peringatan Permukaan Panas', 'Contact with hot surfaces may cause burns.', 'safety', 'medium'],
                        ['electrical-shock-risk', 'Electrical Shock Risk', 'Risiko Sengatan Listrik', 'Product may present an electric shock hazard.', 'safety', 'high'],
                        ['choking-hazard', 'Choking Hazard', 'Bahaya Tersedak', 'Small parts may present a choking hazard.', 'safety', 'high'],
                        ['sharp-object-warning', 'Sharp Object Warning', 'Peringatan Benda Tajam', 'Contains sharp edges or points.', 'safety', 'medium'],
                        ['battery-ingestion-hazard', 'Battery Ingestion Hazard', 'Bahaya Tertelan Baterai', 'Small batteries may cause severe internal injuries if swallowed.', 'safety', 'critical'],
                        ['keep-away-from-heat', 'Keep Away from Heat', 'Jauhkan dari Panas', 'Keep away from heat, sparks and open flames.', 'safety', 'medium'],
                    ],
                ],

                // -------------------------------------------------
                // 8. ENVIRONMENTAL
                // -------------------------------------------------
                [
                    'name' => [
                        'en' => 'Environmental',
                        'id' => 'Lingkungan',
                    ],
                    'slug' => 'environmental',
                    'description' => [
                        'en' => 'Environmental impact, disposal and sustainability information.',
                        'id' => 'Informasi dampak lingkungan, pembuangan dan keberlanjutan.',
                    ],
                    'sort_order' => 8,
                    'labels' => [
                        ['environmental-hazard', 'Environmental Hazard', 'Bahaya Lingkungan', 'Product may cause environmental harm.', 'safety', 'high'],
                        ['marine-pollutant', 'Marine Pollutant', 'Polutan Laut', 'Substance or product is classified as a marine pollutant where applicable.', 'safety', 'high'],
                        ['recyclable', 'Recyclable', 'Dapat Didaur Ulang', 'Product or its material can be recycled where facilities exist.', 'marketing'],
                        ['biodegradable', 'Biodegradable', 'Mudah Terurai Secara Hayati', 'Product or material is claimed to biodegrade under specified conditions.', 'marketing'],
                        ['compostable', 'Compostable', 'Dapat Dikomposkan', 'Product or packaging is certified or claimed compostable under specified conditions.', 'marketing'],
                        ['single-use-plastic', 'Single-Use Plastic', 'Plastik Sekali Pakai', 'Product contains or is packaged in single-use plastic.', 'regulatory'],
                        ['battery-recycling-required', 'Battery Recycling Required', 'Wajib Didaur Ulang sebagai Baterai', 'Battery requires appropriate collection or recycling.', 'safety', 'medium'],
                        ['special-disposal-required', 'Special Disposal Required', 'Wajib Pembuangan Khusus', 'Product requires special disposal procedures.', 'safety', 'high'],
                    ],
                ],
            ];

            foreach ($groups as $groupData) {

                $labels = $groupData['labels'];

                $group = LabelGroup::updateOrCreate(
                    ['slug' => $groupData['slug']],
                    [
                        'name' => $groupData['name'],
                        'description' => $groupData['description'],
                        'sort_order' => $groupData['sort_order'],
                        'is_active' => true,
                    ]
                );

                foreach ($labels as $item) {

                    $severity = $item[5] ?? 'info';

                    Label::updateOrCreate(
                        ['slug' => $item[0]],
                        [
                            'label_group_id' => $group->id,

                            'name' => [
                                'en' => $item[1],
                                'id' => $item[2],
                            ],

                            'description' => [
                                'en' => $item[3],
                                'id' => $item[3],
                            ],

                            'icon' => null,
                            'label_type' => $item[4],
                            'severity' => $severity,
                            'is_active' => true,
                        ]
                    );
                }
            }
        });
    }
}