<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $taxonomy = [
            [
                'en' => 'Electronics & Computers',
                'id' => 'Elektronik & Komputer',
                'children' => [
                    [
                        'en' => 'Cell Phones & Accessories',
                        'id' => 'Ponsel & Aksesori',
                        'children' => [
                            ['en' => 'Smartphones & Unlocked Cell Phones', 'id' => 'Smartphone & Ponsel Tidak Terkunci'],
                            ['en' => 'Cell Phone Cases, Covers & Clips', 'id' => 'Casing, Pelindung & Klip Ponsel'],
                            ['en' => 'Accessories (Chargers, Power Banks, Screen Protectors, Mounts)', 'id' => 'Aksesori (Pengisi Daya, Power Bank, Pelindung Layar, Dudukan)'],
                        ],
                    ],
                    [
                        'en' => 'Computers & Accessories',
                        'id' => 'Komputer & Aksesori',
                        'children' => [
                            ['en' => 'Laptops & Notebooks (Gaming, Ultrabooks, Chromebooks)', 'id' => 'Laptop & Notebook (Gaming, Ultrabook, Chromebook)'],
                            ['en' => 'Desktop Computers (Towers, All-in-Ones, Mini PCs)', 'id' => 'Komputer Desktop (Tower, All-in-One, Mini PC)'],
                            ['en' => 'Computer Components (CPUs, GPUs, Motherboards, RAM, Storage)', 'id' => 'Komponen Komputer (CPU, GPU, Motherboard, RAM, Penyimpanan)'],
                            ['en' => 'Monitors & Accessories', 'id' => 'Monitor & Aksesori'],
                            ['en' => 'Networking Products (Routers, Switches, Mesh Systems)', 'id' => 'Produk Jaringan (Router, Switch, Sistem Mesh)'],
                        ],
                    ],
                    [
                        'en' => 'Audio & Headphones',
                        'id' => 'Audio & Headphone',
                        'children' => [
                            ['en' => 'Headphones & Earbuds (Over-Ear, In-Ear, True Wireless, Noise-Canceling)', 'id' => 'Headphone & Earbud (Over-Ear, In-Ear, True Wireless, Peredam Bising)'],
                            ['en' => 'Bluetooth & Portable Speakers', 'id' => 'Speaker Bluetooth & Portabel'],
                            ['en' => 'Home Audio & Soundbars', 'id' => 'Audio Rumah & Soundbar'],
                        ],
                    ],
                    [
                        'en' => 'Television & Video',
                        'id' => 'Televisi & Video',
                        'children' => [
                            ['en' => 'Televisions (OLED, QLED, 4K Smart TVs)', 'id' => 'Televisi (OLED, QLED, Smart TV 4K)'],
                            ['en' => 'Streaming Media Players', 'id' => 'Pemutar Media Streaming'],
                            ['en' => 'Projectors & Home Theater Systems', 'id' => 'Proyektor & Sistem Home Theater'],
                        ],
                    ],
                    [
                        'en' => 'Cameras & Photography',
                        'id' => 'Kamera & Fotografi',
                        'children' => [
                            ['en' => 'Digital Cameras (DSLR, Mirrorless, Compact)', 'id' => 'Kamera Digital (DSLR, Mirrorless, Kompak)'],
                            ['en' => 'Action Cameras & Camcorders', 'id' => 'Kamera Aksi & Camcorder'],
                            ['en' => 'Camera Lenses & Tripods', 'id' => 'Lensa Kamera & Tripod'],
                            ['en' => 'Drones & Aerial Imaging', 'id' => 'Drone & Pencitraan Udara'],
                        ],
                    ],
                    [
                        'en' => 'Wearable Technology',
                        'id' => 'Teknologi Wearable',
                        'children' => [
                            ['en' => 'Smartwatches & Fitness Trackers', 'id' => 'Smartwatch & Pelacak Kebugaran'],
                            ['en' => 'Smart Rings & Accessories', 'id' => 'Cincin Pintar & Aksesori'],
                        ],
                    ],
                ],
            ],
            [
                'en' => 'Home & Kitchen',
                'id' => 'Rumah & Dapur',
                'children' => [
                    [
                        'en' => 'Kitchen & Dining',
                        'id' => 'Dapur & Peralatan Makan',
                        'children' => [
                            ['en' => 'Cookware (Pots, Pans, Bakeware)', 'id' => 'Peralatan Memasak (Panci, Wajan, Peralatan Panggang)'],
                            ['en' => 'Small Appliances (Air Fryers, Blenders, Coffee Makers, Microwave)', 'id' => 'Peralatan Elektronik Kecil (Air Fryer, Blender, Pembuat Kopi, Microwave)'],
                            ['en' => 'Cutlery & Kitchen Knives', 'id' => 'Alat Makan & Pisau Dapur'],
                            ['en' => 'Kitchen Storage & Organization (Containers, Food Wraps)', 'id' => 'Penyimpanan & Organisasi Dapur (Wadah, Pembungkus Makanan)'],
                            ['en' => 'Tableware & Dinnerware', 'id' => 'Peralatan Meja & Peralatan Makan'],
                        ],
                    ],
                    [
                        'en' => 'Furniture',
                        'id' => 'Furnitur',
                        'children' => [
                            ['en' => 'Living Room Furniture (Sofas, Coffee Tables, TV Stands)', 'id' => 'Furnitur Ruang Tamu (Sofa, Meja Kopi, Meja TV)'],
                            ['en' => 'Bedroom Furniture (Beds, Mattresses, Dressers)', 'id' => 'Furnitur Kamar Tidur (Tempat Tidur, Kasur, Lemari Laci)'],
                            ['en' => 'Home Office Furniture (Desks, Ergonomic Chairs)', 'id' => 'Furnitur Kantor Rumah (Meja Kerja, Kursi Ergonomis)'],
                            ['en' => 'Kitchen & Dining Furniture', 'id' => 'Furnitur Dapur & Ruang Makan'],
                        ],
                    ],
                    [
                        'en' => 'Bedding & Bath',
                        'id' => 'Perlengkapan Tidur & Kamar Mandi',
                        'children' => [
                            ['en' => 'Bedding (Sheets, Pillows, Comforters, Duvet Covers)', 'id' => 'Perlengkapan Tidur (Seprai, Bantal, Selimut, Sarung Duvet)'],
                            ['en' => 'Bath Towels, Mats & Accessories', 'id' => 'Handuk, Keset & Aksesori Kamar Mandi'],
                        ],
                    ],
                    [
                        'en' => 'Home Décor',
                        'id' => 'Dekorasi Rumah',
                        'children' => [
                            ['en' => 'Wall Art, Mirrors & Frames', 'id' => 'Seni Dinding, Cermin & Bingkai'],
                            ['en' => 'Lighting & Ceiling Fans', 'id' => 'Lampu & Kipas Langit-langit'],
                            ['en' => 'Rugs, Curtains & Window Treatments', 'id' => 'Karpet, Gorden & Dekorasi Jendela'],
                            ['en' => 'Clocks, Candles & Vases', 'id' => 'Jam, Lilin & Vas'],
                        ],
                    ],
                    [
                        'en' => 'Storage & Organization',
                        'id' => 'Penyimpanan & Organisasi',
                        'children' => [
                            ['en' => 'Closet Storage & Hangers', 'id' => 'Penyimpanan Lemari & Gantungan'],
                            ['en' => 'Laundry Baskets & Shelving', 'id' => 'Keranjang Cucian & Rak'],
                        ],
                    ],
                    [
                        'en' => 'Heating, Cooling & Air Quality',
                        'id' => 'Pemanas, Pendingin & Kualitas Udara',
                        'children' => [
                            ['en' => 'Air Conditioners & Fans', 'id' => 'Pendingin Udara & Kipas'],
                            ['en' => 'Air Purifiers & Humidifiers', 'id' => 'Pembersih Udara & Pelembap Udara'],
                            ['en' => 'Space Heaters', 'id' => 'Pemanas Ruangan'],
                        ],
                    ],
                ],
            ],
            [
                'en' => 'Major Appliances',
                'id' => 'Peralatan Rumah Tangga Besar',
                'children' => [
                    ['en' => 'Refrigerators & Freezers', 'id' => 'Kulkas & Freezer', 'children' => [
                        ['en' => 'French Door & Side-by-Side Refrigerators', 'id' => 'Kulkas French Door & Side-by-Side'],
                        ['en' => 'Compact & Mini Fridges', 'id' => 'Kulkas Kompak & Mini'],
                        ['en' => 'Chest & Upright Freezers', 'id' => 'Freezer Chest & Tegak'],
                    ]],
                    ['en' => 'Laundry Appliances', 'id' => 'Peralatan Binatu', 'children' => [
                        ['en' => 'Washing Machines (Front Load, Top Load)', 'id' => 'Mesin Cuci (Bukaan Depan, Bukaan Atas)'],
                        ['en' => 'Clothes Dryers', 'id' => 'Mesin Pengering Pakaian'],
                        ['en' => 'Washer Dryer Combos', 'id' => 'Mesin Cuci dan Pengering Kombinasi'],
                    ]],
                    ['en' => 'Cooking Appliances', 'id' => 'Peralatan Memasak', 'children' => [
                        ['en' => 'Ranges, Ovens & Cooktops', 'id' => 'Kompor, Oven & Kompor Tanam'],
                        ['en' => 'Range Hoods & Exhaust Fans', 'id' => 'Penghisap Asap Dapur & Kipas Exhaust'],
                    ]],
                    ['en' => 'Dishwashers', 'id' => 'Mesin Pencuci Piring', 'children' => [
                        ['en' => 'Built-In Dishwashers', 'id' => 'Mesin Pencuci Piring Tanam'],
                        ['en' => 'Portable & Countertop Dishwashers', 'id' => 'Mesin Pencuci Piring Portabel & Meja'],
                    ]],
                ],
            ],
            [
                'en' => 'Beauty & Personal Care',
                'id' => 'Kecantikan & Perawatan Diri',
                'children' => [
                    ['en' => 'Skincare', 'id' => 'Perawatan Kulit', 'children' => [
                        ['en' => 'Face Cleansers & Toners', 'id' => 'Pembersih Wajah & Toner'],
                        ['en' => 'Serums, Moisturizers & Treatments', 'id' => 'Serum, Pelembap & Perawatan'],
                        ['en' => 'Sunscreens & Tanning Products', 'id' => 'Tabir Surya & Produk Penggelap Kulit'],
                        ['en' => 'Eye Creams & Lip Care', 'id' => 'Krim Mata & Perawatan Bibir'],
                    ]],
                    ['en' => 'Makeup', 'id' => 'Riasan', 'children' => [
                        ['en' => 'Face Makeup (Foundation, Concealer, Powder)', 'id' => 'Riasan Wajah (Foundation, Concealer, Bedak)'],
                        ['en' => 'Eye Makeup (Mascara, Eyeliner, Eyeshadow)', 'id' => 'Riasan Mata (Maskara, Eyeliner, Eyeshadow)'],
                        ['en' => 'Lip Makeup (Lipstick, Gloss, Lip Liner)', 'id' => 'Riasan Bibir (Lipstik, Gloss, Lip Liner)'],
                    ]],
                    ['en' => 'Hair Care', 'id' => 'Perawatan Rambut', 'children' => [
                        ['en' => 'Shampoo & Conditioners', 'id' => 'Sampo & Kondisioner'],
                        ['en' => 'Hair Styling Tools (Hair Dryers, Straighteners, Curling Irons)', 'id' => 'Alat Penata Rambut (Pengering, Pelurus, Pengeriting)'],
                        ['en' => 'Hair Coloring & Treatments', 'id' => 'Pewarna & Perawatan Rambut'],
                    ]],
                    ['en' => 'Fragrance', 'id' => 'Wewangian', 'children' => [
                        ['en' => "Women's Perfume & Eau de Parfum", 'id' => 'Parfum Wanita & Eau de Parfum'],
                        ['en' => "Men's Cologne & Aftershaves", 'id' => 'Kolonye Pria & Aftershave'],
                    ]],
                    ['en' => 'Personal Care & Oral Care', 'id' => 'Perawatan Diri & Mulut', 'children' => [
                        ['en' => 'Electric Toothbrushes & Dental Care', 'id' => 'Sikat Gigi Elektrik & Perawatan Gigi'],
                        ['en' => 'Shaving & Hair Removal (Razors, Trimmers)', 'id' => 'Mencukur & Penghilang Rambut (Alat Cukur, Trimmer)'],
                        ['en' => 'Bath & Body Wash', 'id' => 'Sabun Mandi & Pembersih Tubuh'],
                    ]],
                ],
            ],
            [
                'en' => 'Health & Household',
                'id' => 'Kesehatan & Rumah Tangga',
                'children' => [
                    ['en' => 'Vitamins & Dietary Supplements', 'id' => 'Vitamin & Suplemen Makanan', 'children' => [
                        ['en' => 'Multivitamins & Minerals', 'id' => 'Multivitamin & Mineral'],
                        ['en' => 'Protein Powders & Sports Nutrition', 'id' => 'Bubuk Protein & Nutrisi Olahraga'],
                        ['en' => 'Herbal & Botanical Supplements', 'id' => 'Suplemen Herbal & Nabati'],
                    ]],
                    ['en' => 'Household Essentials', 'id' => 'Kebutuhan Rumah Tangga', 'children' => [
                        ['en' => 'Laundry Detergents & Fabric Softeners', 'id' => 'Deterjen & Pelembut Pakaian'],
                        ['en' => 'Paper Products (Toilet Paper, Paper Towels)', 'id' => 'Produk Kertas (Tisu Toilet, Tisu Dapur)'],
                        ['en' => 'Cleaning Supplies & Disinfectants', 'id' => 'Perlengkapan Pembersih & Disinfektan'],
                    ]],
                    ['en' => 'Personal Care & Wellness', 'id' => 'Perawatan Diri & Kebugaran', 'children' => [
                        ['en' => 'First Aid Supplies & Bandages', 'id' => 'Perlengkapan P3K & Perban'],
                        ['en' => 'Pain Relievers & OTC Medicine', 'id' => 'Pereda Nyeri & Obat Bebas'],
                        ['en' => 'Sleep Aids & Allergy Relief', 'id' => 'Bantuan Tidur & Pereda Alergi'],
                    ]],
                    ['en' => 'Medical Supplies & Equipment', 'id' => 'Perlengkapan & Peralatan Medis', 'children' => [
                        ['en' => 'Blood Pressure Monitors & Thermometers', 'id' => 'Alat Ukur Tekanan Darah & Termometer'],
                        ['en' => 'Mobility Aids & Wheelchairs', 'id' => 'Alat Bantu Mobilitas & Kursi Roda'],
                    ]],
                ],
            ],
            [
                'en' => 'Grocery & Gourmet Food',
                'id' => 'Bahan Makanan & Makanan Premium',
                'children' => [
                    ['en' => 'Beverages', 'id' => 'Minuman', 'children' => [
                        ['en' => 'Coffee (Beans, Ground, Pods/Capsules)', 'id' => 'Kopi (Biji, Bubuk, Pod/Kapsul)'],
                        ['en' => 'Tea & Herbal Tea', 'id' => 'Teh & Teh Herbal'],
                        ['en' => 'Juices, Soft Drinks & Energy Drinks', 'id' => 'Jus, Minuman Ringan & Minuman Energi'],
                        ['en' => 'Water & Sparkling Water', 'id' => 'Air Mineral & Air Berkarbonasi'],
                    ]],
                    ['en' => 'Pantry Staples', 'id' => 'Bahan Pokok Dapur', 'children' => [
                        ['en' => 'Cooking Oils, Vinegars & Spices', 'id' => 'Minyak Goreng, Cuka & Rempah'],
                        ['en' => 'Sauces, Condiments & Dressings', 'id' => 'Saus, Bumbu & Dressing'],
                        ['en' => 'Pasta, Rice & Grains', 'id' => 'Pasta, Beras & Biji-bijian'],
                        ['en' => 'Baking Ingredients', 'id' => 'Bahan Membuat Kue'],
                    ]],
                    ['en' => 'Snacks & Sweets', 'id' => 'Camilan & Makanan Manis', 'children' => [
                        ['en' => 'Chips, Crackers & Nuts', 'id' => 'Keripik, Kerupuk & Kacang'],
                        ['en' => 'Chocolate & Candies', 'id' => 'Cokelat & Permen'],
                        ['en' => 'Cookies & Snack Bars', 'id' => 'Kukis & Bar Camilan'],
                    ]],
                ],
            ],
            [
                'en' => 'Clothing, Shoes & Jewelry',
                'id' => 'Pakaian, Sepatu & Perhiasan',
                'children' => [
                    ['en' => "Women's Fashion", 'id' => 'Mode Wanita', 'children' => [
                        ['en' => 'Clothing (Dresses, Tops, Pants, Jackets)', 'id' => 'Pakaian (Gaun, Atasan, Celana, Jaket)'],
                        ['en' => 'Shoes (Sneakers, Heels, Boots, Sandals)', 'id' => 'Sepatu (Sneaker, Hak Tinggi, Bot, Sandal)'],
                        ['en' => 'Jewelry (Necklaces, Earrings, Rings)', 'id' => 'Perhiasan (Kalung, Anting, Cincin)'],
                        ['en' => 'Handbags & Wallets', 'id' => 'Tas Tangan & Dompet'],
                    ]],
                    ['en' => "Men's Fashion", 'id' => 'Mode Pria', 'children' => [
                        ['en' => 'Clothing (Shirts, T-Shirts, Trousers, Suits)', 'id' => 'Pakaian (Kemeja, Kaos, Celana, Jas)'],
                        ['en' => 'Shoes (Sneakers, Formal Shoes, Boots)', 'id' => 'Sepatu (Sneaker, Sepatu Formal, Bot)'],
                        ['en' => 'Watches & Accessories (Belts, Wallets)', 'id' => 'Jam Tangan & Aksesori (Ikat Pinggang, Dompet)'],
                    ]],
                    ['en' => 'Kids & Baby Fashion', 'id' => 'Mode Anak & Bayi', 'children' => [
                        ['en' => 'Baby Clothing & Shoes', 'id' => 'Pakaian & Sepatu Bayi'],
                        ['en' => "Girls' & Boys' Apparel", 'id' => 'Pakaian Anak Perempuan & Laki-laki'],
                    ]],
                ],
            ],
            [
                'en' => 'Tools & Home Improvement',
                'id' => 'Perkakas & Perbaikan Rumah',
                'children' => [
                    ['en' => 'Power & Hand Tools', 'id' => 'Perkakas Listrik & Manual', 'children' => [
                        ['en' => 'Power Tools (Drills, Saws, Grinders)', 'id' => 'Perkakas Listrik (Bor, Gergaji, Gerinda)'],
                        ['en' => 'Hand Tools (Hammers, Screwdrivers, Pliers)', 'id' => 'Perkakas Manual (Palu, Obeng, Tang)'],
                        ['en' => 'Tool Sets & Organizers', 'id' => 'Set Perkakas & Organizer'],
                    ]],
                    ['en' => 'Building Supplies & Hardware', 'id' => 'Perlengkapan Bangunan & Perangkat Keras', 'children' => [
                        ['en' => 'Cabinet Hardware & Locks', 'id' => 'Perangkat Lemari & Kunci'],
                        ['en' => 'Plumbing Fixtures (Faucets, Sinks)', 'id' => 'Perlengkapan Pipa (Keran, Bak Cuci)'],
                        ['en' => 'Electrical Switches, Outlets & Wiring', 'id' => 'Sakelar, Stopkontak & Kabel Listrik'],
                    ]],
                    ['en' => 'Safety & Security', 'id' => 'Keselamatan & Keamanan', 'children' => [
                        ['en' => 'Security Cameras & Alarm Systems', 'id' => 'Kamera Keamanan & Sistem Alarm'],
                        ['en' => 'Smart Door Locks & Video Doorbells', 'id' => 'Kunci Pintu Pintar & Bel Pintu Video'],
                    ]],
                ],
            ],
            [
                'en' => 'Patio, Lawn & Garden',
                'id' => 'Teras, Halaman & Taman',
                'children' => [
                    ['en' => 'Outdoor Power Equipment', 'id' => 'Peralatan Listrik Luar Ruangan', 'children' => [
                        ['en' => 'Lawn Mowers & Trimmers', 'id' => 'Mesin Pemotong Rumput & Pemangkas'],
                        ['en' => 'Pressure Washers & Leaf Blowers', 'id' => 'Mesin Cuci Tekanan Tinggi & Peniup Daun'],
                    ]],
                    ['en' => 'Patio Furniture & Accessories', 'id' => 'Furnitur Teras & Aksesori', 'children' => [
                        ['en' => 'Patio Dining Sets & Loungers', 'id' => 'Set Meja Makan Teras & Kursi Santai'],
                        ['en' => 'Outdoor Umbrellas & Gazebos', 'id' => 'Payung Luar Ruangan & Gazebo'],
                        ['en' => 'Fire Pits & Outdoor Heating', 'id' => 'Tungku Api & Pemanas Luar Ruangan'],
                    ]],
                    ['en' => 'Grills & Outdoor Cooking', 'id' => 'Pemanggang & Memasak Luar Ruangan', 'children' => [
                        ['en' => 'Gas, Charcoal & Pellet Grills', 'id' => 'Pemanggang Gas, Arang & Pelet'],
                        ['en' => 'BBQ Tools & Accessories', 'id' => 'Perkakas & Aksesori BBQ'],
                    ]],
                    ['en' => 'Gardening & Lawn Care', 'id' => 'Berkebun & Perawatan Rumput', 'children' => [
                        ['en' => 'Soils, Fertilizers & Plant Food', 'id' => 'Tanah, Pupuk & Nutrisi Tanaman'],
                        ['en' => 'Watering Equipment & Hoses', 'id' => 'Peralatan Penyiraman & Selang'],
                    ]],
                ],
            ],
            [
                'en' => 'Sports & Outdoors',
                'id' => 'Olahraga & Aktivitas Luar Ruangan',
                'children' => [
                    ['en' => 'Athletic & Exercise Equipment', 'id' => 'Peralatan Atletik & Olahraga', 'children' => [
                        ['en' => 'Cardio Equipment (Treadmills, Exercise Bikes)', 'id' => 'Peralatan Kardio (Treadmill, Sepeda Statis)'],
                        ['en' => 'Strength Training (Dumbbells, Barbells, Kettlebells)', 'id' => 'Latihan Kekuatan (Dumbbell, Barbel, Kettlebell)'],
                        ['en' => 'Yoga & Pilates Mats/Accessories', 'id' => 'Matras & Aksesori Yoga/Pilates'],
                    ]],
                    ['en' => 'Outdoor Recreation', 'id' => 'Rekreasi Luar Ruangan', 'children' => [
                        ['en' => 'Camping & Hiking (Tents, Sleeping Bags, Backpacks)', 'id' => 'Berkemah & Mendaki (Tenda, Kantong Tidur, Ransel)'],
                        ['en' => 'Cycling (Bikes, Helmets, Repair Tools)', 'id' => 'Bersepeda (Sepeda, Helm, Perkakas Perbaikan)'],
                        ['en' => 'Fishing & Hunting Gear', 'id' => 'Perlengkapan Memancing & Berburu'],
                        ['en' => 'Water Sports (Kayaks, Paddleboards)', 'id' => 'Olahraga Air (Kayak, Papan Dayung)'],
                    ]],
                ],
            ],
            [
                'en' => 'Toys & Games',
                'id' => 'Mainan & Permainan',
                'children' => [
                    ['en' => 'Building Toys', 'id' => 'Mainan Konstruksi', 'children' => [
                        ['en' => 'Building Blocks & Lego Sets', 'id' => 'Balok Susun & Set LEGO'],
                    ]],
                    ['en' => 'Dolls & Action Figures', 'id' => 'Boneka & Figur Aksi', 'children' => [
                        ['en' => 'Action Figures & Statues', 'id' => 'Figur Aksi & Patung Koleksi'],
                        ['en' => 'Dolls, Playsets & Accessories', 'id' => 'Boneka, Set Bermain & Aksesori'],
                    ]],
                    ['en' => 'Board Games & Puzzles', 'id' => 'Permainan Papan & Puzzle', 'children' => [
                        ['en' => 'Board Games & Card Games', 'id' => 'Permainan Papan & Kartu'],
                        ['en' => 'Jigsaw Puzzles & Brain Teasers', 'id' => 'Puzzle Jigsaw & Asah Otak'],
                    ]],
                    ['en' => 'Remote Control & Hobbies', 'id' => 'Kendali Jarak Jauh & Hobi', 'children' => [
                        ['en' => 'RC Vehicles & Drones', 'id' => 'Kendaraan RC & Drone'],
                        ['en' => 'Model Building Kits', 'id' => 'Kit Perakitan Model'],
                    ]],
                ],
            ],
            [
                'en' => 'Automotive & Powersports',
                'id' => 'Otomotif & Kendaraan Olahraga',
                'children' => [
                    ['en' => 'Car Care & Detailing', 'id' => 'Perawatan & Detailing Mobil', 'children' => [
                        ['en' => 'Car Wash, Wax & Polishes', 'id' => 'Sampo Mobil, Wax & Poles'],
                        ['en' => 'Interior Cleaners', 'id' => 'Pembersih Interior'],
                    ]],
                    ['en' => 'Replacement Parts', 'id' => 'Suku Cadang Pengganti', 'children' => [
                        ['en' => 'Brake Pads & Rotors', 'id' => 'Kampas Rem & Rotor'],
                        ['en' => 'Oil Filters, Air Filters & Spark Plugs', 'id' => 'Filter Oli, Filter Udara & Busi'],
                        ['en' => 'Car Batteries', 'id' => 'Aki Mobil'],
                    ]],
                    ['en' => 'Car Electronics', 'id' => 'Elektronik Mobil', 'children' => [
                        ['en' => 'Car Audio, Head Units & Speakers', 'id' => 'Audio Mobil, Head Unit & Speaker'],
                        ['en' => 'Dash Cams & GPS Navigation', 'id' => 'Dashcam & Navigasi GPS'],
                    ]],
                ],
            ],
            [
                'en' => 'Pet Supplies',
                'id' => 'Perlengkapan Hewan Peliharaan',
                'children' => [
                    ['en' => 'Dogs', 'id' => 'Anjing', 'children' => [
                        ['en' => 'Dog Food & Treats', 'id' => 'Makanan & Camilan Anjing'],
                        ['en' => 'Dog Toys, Beds & Crates', 'id' => 'Mainan, Tempat Tidur & Kandang Anjing'],
                        ['en' => 'Grooming & Health Supplies', 'id' => 'Perlengkapan Perawatan & Kesehatan'],
                    ]],
                    ['en' => 'Cats', 'id' => 'Kucing', 'children' => [
                        ['en' => 'Cat Food & Treats', 'id' => 'Makanan & Camilan Kucing'],
                        ['en' => 'Cat Litter & Boxes', 'id' => 'Pasir & Kotak Kotoran Kucing'],
                        ['en' => 'Trees, Scratchers & Toys', 'id' => 'Pohon Kucing, Penggaruk & Mainan'],
                    ]],
                    ['en' => 'Fish, Birds & Small Animals', 'id' => 'Ikan, Burung & Hewan Kecil', 'children' => [
                        ['en' => 'Aquariums, Filters & Fish Food', 'id' => 'Akuarium, Filter & Makanan Ikan'],
                    ]],
                ],
            ],
        ];

        foreach ($taxonomy as $rootOrder => $rootData) {
            $root = $this->saveCategory($rootData, null, $rootOrder + 1);

            foreach ($rootData['children'] as $childOrder => $childData) {
                $child = $this->saveCategory($childData, $root->id, $childOrder + 1);

                foreach (($childData['children'] ?? []) as $leafOrder => $leafData) {
                    $this->saveCategory($leafData, $child->id, $leafOrder + 1);
                }
            }
        }
    }

    private function saveCategory(array $data, ?int $parentId, int $sortOrder): Category
    {
        $name = [
            'en' => $data['en'],
            'id' => $data['id'],
        ];

        $slug = Str::slug($data['en']);

        return Category::updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'parent_id' => $parentId,
                'sort_order' => $sortOrder,
                'is_active' => true,
            ]
        );
    }
}
