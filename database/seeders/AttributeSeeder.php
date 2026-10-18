<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $definitions = [

            /*
            |--------------------------------------------------------------------------
            | Smartphones
            |--------------------------------------------------------------------------
            */
            'smartphones-unlocked-cell-phones' => [
                'Design' => [
                    ['weight', 'weight', 'berat', 'Weight', 'Berat', 'g', 'number', true, true],
                    ['height', 'height', 'tinggi', 'Height', 'Tinggi', 'mm', 'number', true, true],
                    ['width', 'width', 'lebar', 'Width', 'Lebar', 'mm', 'number', true, true],
                    ['thickness', 'thickness', 'ketebalan', 'Thickness', 'Ketebalan', 'mm', 'number', true, true],
                    ['build_material', 'build material', 'material bodi', 'Build material', 'Material bodi', null, 'select', true, true],
                    ['water_resistance', 'water resistance', 'ketahanan air', 'Water resistance', 'Ketahanan air', null, 'select', true, true],
                ],
                'Display' => [
                    ['screen_size', 'screen size', 'ukuran layar', 'Screen size', 'Ukuran layar', 'inch', 'number', true, true],
                    ['display_type', 'display type', 'jenis layar', 'Display type', 'Jenis layar', null, 'select', true, true],
                    ['resolution', 'resolution', 'resolusi', 'Resolution', 'Resolusi', null, 'text', true, true],
                    ['refresh_rate', 'refresh rate', 'refresh rate', 'Refresh rate', 'Refresh rate', 'Hz', 'number', true, true],
                    ['peak_brightness', 'peak brightness', 'kecerahan puncak', 'Peak brightness', 'Kecerahan puncak', 'nits', 'number', true, true],
                    ['screen_protection', 'screen protection', 'perlindungan layar', 'Screen protection', 'Perlindungan layar', null, 'text', false, true],
                ],
                'Performance' => [
                    ['processor', 'processor', 'prosesor', 'Processor', 'Prosesor', null, 'text', true, true],
                    ['cpu_cores', 'cpu cores', 'inti CPU', 'CPU cores', 'Inti CPU', null, 'number', true, true],
                    ['cpu_frequency', 'cpu frequency', 'frekuensi CPU', 'CPU frequency', 'Frekuensi CPU', 'GHz', 'number', true, true],
                    ['gpu', 'gpu', 'GPU', 'GPU', 'GPU', null, 'text', true, true],
                ],
                'Memory' => [
                    ['ram', 'ram', 'RAM', 'RAM', 'RAM', 'GB', 'number', true, true],
                    ['internal_storage', 'internal storage', 'penyimpanan internal', 'Internal storage', 'Penyimpanan internal', 'GB', 'number', true, true],
                    ['expandable_storage', 'expandable storage', 'penyimpanan tambahan', 'Expandable storage', 'Penyimpanan tambahan', null, 'boolean', true, true],
                ],
                'Cameras' => [
                    ['main_camera_resolution', 'main camera resolution', 'resolusi kamera utama', 'Main camera resolution', 'Resolusi kamera utama', 'MP', 'number', true, true],
                    ['ultrawide_camera', 'ultrawide camera', 'kamera ultrawide', 'Ultrawide camera', 'Kamera ultrawide', null, 'boolean', true, true],
                    ['telephoto_camera', 'telephoto camera', 'kamera telefoto', 'Telephoto camera', 'Kamera telefoto', null, 'boolean', true, true],
                    ['optical_zoom', 'optical zoom', 'zoom optik', 'Optical zoom', 'Zoom optik', 'x', 'number', true, true],
                    ['front_camera_resolution', 'front camera resolution', 'resolusi kamera depan', 'Front camera resolution', 'Resolusi kamera depan', 'MP', 'number', true, true],
                    ['max_video_resolution', 'max video resolution', 'resolusi video maksimum', 'Max video resolution', 'Resolusi video maksimum', null, 'text', true, true],
                ],
                'Battery' => [
                    ['battery_capacity', 'battery capacity', 'kapasitas baterai', 'Battery capacity', 'Kapasitas baterai', 'mAh', 'number', true, true],
                    ['wired_charging', 'wired charging', 'pengisian kabel', 'Wired charging', 'Pengisian kabel', 'W', 'number', true, true],
                    ['wireless_charging', 'wireless charging', 'pengisian nirkabel', 'Wireless charging', null, null, 'boolean', true, true],
                    ['battery_removable', 'removable battery', 'baterai dapat dilepas', 'Removable battery', 'Baterai dapat dilepas', null, 'boolean', false, true],
                ],
                'Audio' => [
                    ['stereo_speakers', 'stereo speakers', 'speaker stereo', 'Stereo speakers', 'Speaker stereo', null, 'boolean', true, true],
                    ['headphone_jack', 'headphone jack', 'jack headphone', 'Headphone jack', 'Jack headphone', null, 'boolean', true, true],
                ],
                'Connectivity & Features' => [
                    ['network_5g', '5G support', 'dukungan 5G', '5G support', 'Dukungan 5G', null, 'boolean', true, true],
                    ['wifi_version', 'wifi version', 'versi Wi-Fi', 'Wi-Fi version', 'Versi Wi-Fi', null, 'text', true, true],
                    ['bluetooth_version', 'bluetooth version', 'versi Bluetooth', 'Bluetooth version', 'Versi Bluetooth', null, 'text', true, true],
                    ['nfc', 'NFC', 'NFC', 'NFC', 'NFC', null, 'boolean', true, true],
                    ['usb_type', 'usb type', 'jenis USB', 'USB type', 'Jenis USB', null, 'text', true, true],
                    ['fingerprint_sensor', 'fingerprint sensor', 'sensor sidik jari', 'Fingerprint sensor', 'Sensor sidik jari', null, 'boolean', true, true],
                ],
                'Software' => [
                    ['operating_system', 'operating system', 'sistem operasi', 'Operating system', 'Sistem operasi', null, 'text', true, true],
                    ['os_update_support', 'os update support', 'dukungan pembaruan OS', 'OS update support', 'Dukungan pembaruan OS', 'years', 'number', true, true],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Laptops
            |--------------------------------------------------------------------------
            */
            'laptops-notebooks-gaming-ultrabooks-chromebooks' => [
                'Design & Mobility' => [
                    ['laptop_weight', 'weight', 'berat', 'Weight', 'Berat', 'kg', 'number', true, true],
                    ['laptop_thickness', 'thickness', 'ketebalan', 'Thickness', 'Ketebalan', 'mm', 'number', true, true],
                    ['laptop_material', 'body material', 'material bodi', 'Body material', 'Material bodi', null, 'select', true, true],
                ],
                'Display' => [
                    ['laptop_screen_size', 'screen size', 'ukuran layar', 'Screen size', 'Ukuran layar', 'inch', 'number', true, true],
                    ['laptop_resolution', 'resolution', 'resolusi', 'Resolution', 'Resolusi', null, 'text', true, true],
                    ['laptop_panel_type', 'panel type', 'jenis panel', 'Panel type', 'Jenis panel', null, 'select', true, true],
                    ['laptop_refresh_rate', 'refresh rate', 'refresh rate', 'Refresh rate', 'Refresh rate', 'Hz', 'number', true, true],
                    ['touchscreen', 'touchscreen', 'layar sentuh', 'Touchscreen', 'Layar sentuh', null, 'boolean', true, true],
                ],
                'Processor' => [
                    ['laptop_processor', 'processor', 'prosesor', 'Processor', 'Prosesor', null, 'text', true, true],
                    ['laptop_cpu_cores', 'cpu cores', 'inti CPU', 'CPU cores', 'Inti CPU', null, 'number', true, true],
                    ['laptop_cpu_frequency', 'cpu frequency', 'frekuensi CPU', 'CPU frequency', 'Frekuensi CPU', 'GHz', 'number', true, true],
                ],
                'Graphics' => [
                    ['graphics_type', 'graphics type', 'jenis grafis', 'Graphics type', 'Jenis grafis', null, 'select', true, true],
                    ['graphics_processor', 'graphics processor', 'prosesor grafis', 'Graphics processor', 'Prosesor grafis', null, 'text', true, true],
                    ['graphics_memory', 'graphics memory', 'memori grafis', 'Graphics memory', 'Memori grafis', 'GB', 'number', true, true],
                ],
                'Memory & Storage' => [
                    ['laptop_ram', 'ram', 'RAM', 'RAM', 'RAM', 'GB', 'number', true, true],
                    ['ram_type', 'ram type', 'jenis RAM', 'RAM type', 'Jenis RAM', null, 'text', true, true],
                    ['laptop_storage', 'storage capacity', 'kapasitas penyimpanan', 'Storage capacity', 'Kapasitas penyimpanan', 'GB', 'number', true, true],
                    ['storage_type', 'storage type', 'jenis penyimpanan', 'Storage type', 'Jenis penyimpanan', null, 'select', true, true],
                ],
                'Battery' => [
                    ['laptop_battery_capacity', 'battery capacity', 'kapasitas baterai', 'Battery capacity', 'Kapasitas baterai', 'Wh', 'number', true, true],
                    ['claimed_battery_life', 'claimed battery life', 'daya tahan baterai klaim', 'Claimed battery life', 'Daya tahan baterai klaim', 'hours', 'number', true, true],
                ],
                'Connectivity & Ports' => [
                    ['laptop_wifi', 'wifi standard', 'standar Wi-Fi', 'Wi-Fi standard', 'Standar Wi-Fi', null, 'text', true, true],
                    ['laptop_bluetooth', 'bluetooth version', 'versi Bluetooth', 'Bluetooth version', 'Versi Bluetooth', null, 'text', true, true],
                    ['usb_c_ports', 'usb-c ports', 'port USB-C', 'USB-C ports', 'Port USB-C', null, 'number', true, true],
                    ['hdmi_port', 'hdmi port', 'port HDMI', 'HDMI port', 'Port HDMI', null, 'boolean', true, true],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Headphones & Earbuds
            |--------------------------------------------------------------------------
            */
            'headphones-earbuds-over-ear-in-ear-true-wireless-noise-canceling' => [
                'Design & Comfort' => [
                    ['headphone_type', 'headphone type', 'jenis headphone', 'Headphone type', 'Jenis headphone', null, 'select', true, true],
                    ['headphone_weight', 'weight', 'berat', 'Weight', 'Berat', 'g', 'number', true, true],
                    ['foldable', 'foldable design', 'desain lipat', 'Foldable design', 'Desain lipat', null, 'boolean', true, true],
                    ['headphone_water_resistance', 'water resistance', 'ketahanan air', 'Water resistance', 'Ketahanan air', null, 'text', true, true],
                ],
                'Sound Quality' => [
                    ['driver_size', 'driver size', 'ukuran driver', 'Driver size', 'Ukuran driver', 'mm', 'number', true, true],
                    ['frequency_response', 'frequency response', 'respons frekuensi', 'Frequency response', 'Respons frekuensi', 'Hz', 'text', false, true],
                    ['impedance', 'impedance', 'impedansi', 'Impedance', 'Impedansi', 'ohm', 'number', false, true],
                ],
                'Noise Cancellation' => [
                    ['active_noise_cancellation', 'active noise cancellation', 'peredam bising aktif', 'Active noise cancellation', 'Peredam bising aktif', null, 'boolean', true, true],
                    ['transparency_mode', 'transparency mode', 'mode transparansi', 'Transparency mode', 'Mode transparansi', null, 'boolean', true, true],
                ],
                'Battery' => [
                    ['headphone_battery_life', 'battery life', 'daya tahan baterai', 'Battery life', 'Daya tahan baterai', 'hours', 'number', true, true],
                    ['charging_case_battery', 'charging case battery', 'baterai casing pengisi daya', 'Charging case battery', 'Baterai casing pengisi daya', 'hours', 'number', true, true],
                    ['headphone_charging_time', 'charging time', 'waktu pengisian', 'Charging time', 'Waktu pengisian', 'minutes', 'number', false, true],
                ],
                'Connectivity' => [
                    ['headphone_bluetooth', 'bluetooth version', 'versi Bluetooth', 'Bluetooth version', 'Versi Bluetooth', null, 'text', true, true],
                    ['multipoint_connection', 'multipoint connection', 'koneksi multipoint', 'Multipoint connection', 'Koneksi multipoint', null, 'boolean', true, true],
                    ['headphone_codec', 'supported audio codecs', 'codec audio yang didukung', 'Supported audio codecs', 'Codec audio yang didukung', null, 'text', false, true],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Televisions
            |--------------------------------------------------------------------------
            */
            'televisions-oled-qled-4k-smart-tvs' => [
                'Display' => [
                    ['tv_screen_size', 'screen size', 'ukuran layar', 'Screen size', 'Ukuran layar', 'inch', 'number', true, true],
                    ['tv_resolution', 'resolution', 'resolusi', 'Resolution', 'Resolusi', null, 'text', true, true],
                    ['tv_panel_type', 'panel type', 'jenis panel', 'Panel type', 'Jenis panel', null, 'select', true, true],
                    ['tv_refresh_rate', 'refresh rate', 'refresh rate', 'Refresh rate', 'Refresh rate', 'Hz', 'number', true, true],
                    ['tv_hdr', 'hdr support', 'dukungan HDR', 'HDR support', 'Dukungan HDR', null, 'text', true, true],
                ],
                'Picture Quality' => [
                    ['tv_peak_brightness', 'peak brightness', 'kecerahan puncak', 'Peak brightness', 'Kecerahan puncak', 'nits', 'number', true, true],
                    ['local_dimming', 'local dimming', 'local dimming', 'Local dimming', 'Local dimming', null, 'boolean', true, true],
                    ['tv_color_technology', 'color technology', 'teknologi warna', 'Color technology', 'Teknologi warna', null, 'text', false, true],
                ],
                'Audio' => [
                    ['tv_audio_power', 'audio output power', 'daya keluaran audio', 'Audio output power', 'Daya keluaran audio', 'W', 'number', true, true],
                    ['tv_dolby_atmos', 'dolby atmos', 'Dolby Atmos', 'Dolby Atmos', 'Dolby Atmos', null, 'boolean', true, true],
                ],
                'Smart Features' => [
                    ['tv_operating_system', 'operating system', 'sistem operasi', 'Operating system', 'Sistem operasi', null, 'text', true, true],
                    ['voice_control', 'voice control', 'kontrol suara', 'Voice control', 'Kontrol suara', null, 'boolean', true, true],
                ],
                'Connectivity' => [
                    ['tv_hdmi_ports', 'hdmi ports', 'port HDMI', 'HDMI ports', 'Port HDMI', null, 'number', true, true],
                    ['tv_usb_ports', 'usb ports', 'port USB', 'USB ports', 'Port USB', null, 'number', true, true],
                    ['tv_wifi', 'wifi support', 'dukungan Wi-Fi', 'Wi-Fi support', 'Dukungan Wi-Fi', null, 'boolean', true, true],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Digital Cameras
            |--------------------------------------------------------------------------
            */
            'digital-cameras-dslr-mirrorless-compact' => [
                'Image Quality' => [
                    ['camera_resolution', 'effective resolution', 'resolusi efektif', 'Effective resolution', 'Resolusi efektif', 'MP', 'number', true, true],
                    ['camera_sensor_type', 'sensor type', 'jenis sensor', 'Sensor type', 'Jenis sensor', null, 'select', true, true],
                    ['camera_iso_range', 'iso range', 'rentang ISO', 'ISO range', 'Rentang ISO', null, 'text', false, true],
                ],
                'Sensor & Optics' => [
                    ['camera_sensor_size', 'sensor size', 'ukuran sensor', 'Sensor size', 'Ukuran sensor', null, 'text', true, true],
                    ['camera_mount', 'lens mount', 'mount lensa', 'Lens mount', 'Mount lensa', null, 'text', true, true],
                    ['image_stabilization', 'image stabilization', 'stabilisasi gambar', 'Image stabilization', 'Stabilisasi gambar', null, 'boolean', true, true],
                ],
                'Video' => [
                    ['camera_max_video', 'maximum video resolution', 'resolusi video maksimum', 'Maximum video resolution', 'Resolusi video maksimum', null, 'text', true, true],
                    ['camera_video_fps', 'maximum video frame rate', 'frame rate video maksimum', 'Maximum video frame rate', 'Frame rate video maksimum', 'fps', 'number', true, true],
                ],
                'Autofocus & Speed' => [
                    ['camera_af_points', 'autofocus points', 'titik autofocus', 'Autofocus points', 'Titik autofocus', null, 'number', false, true],
                    ['camera_burst_rate', 'continuous shooting speed', 'kecepatan pemotretan beruntun', 'Continuous shooting speed', 'Kecepatan pemotretan beruntun', 'fps', 'number', true, true],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Smartwatches
            |--------------------------------------------------------------------------
            */
            'smartwatches-fitness-trackers' => [
                'Design & Display' => [
                    ['watch_weight', 'weight', 'berat', 'Weight', 'Berat', 'g', 'number', true, true],
                    ['watch_screen_size', 'screen size', 'ukuran layar', 'Screen size', 'Ukuran layar', 'inch', 'number', true, true],
                    ['watch_display_type', 'display type', 'jenis layar', 'Display type', 'Jenis layar', null, 'select', true, true],
                    ['watch_water_resistance', 'water resistance', 'ketahanan air', 'Water resistance', 'Ketahanan air', null, 'text', true, true],
                ],
                'Health & Fitness' => [
                    ['heart_rate_monitor', 'heart rate monitor', 'pemantau detak jantung', 'Heart rate monitor', 'Pemantau detak jantung', null, 'boolean', true, true],
                    ['blood_oxygen_monitor', 'blood oxygen monitoring', 'pemantauan oksigen darah', 'Blood oxygen monitoring', 'Pemantauan oksigen darah', null, 'boolean', true, true],
                    ['built_in_gps', 'built-in gps', 'GPS bawaan', 'Built-in GPS', 'GPS bawaan', null, 'boolean', true, true],
                    ['ecg_support', 'ecg support', 'dukungan ECG', 'ECG support', 'Dukungan ECG', null, 'boolean', true, true],
                ],
                'Battery' => [
                    ['watch_battery_life', 'battery life', 'daya tahan baterai', 'Battery life', 'Daya tahan baterai', 'days', 'number', true, true],
                    ['watch_charging_time', 'charging time', 'waktu pengisian', 'Charging time', 'Waktu pengisian', 'minutes', 'number', false, true],
                ],
                'Connectivity' => [
                    ['watch_bluetooth', 'bluetooth version', 'versi Bluetooth', 'Bluetooth version', 'Versi Bluetooth', null, 'text', true, true],
                    ['watch_cellular', 'cellular connectivity', 'konektivitas seluler', 'Cellular connectivity', 'Konektivitas seluler', null, 'boolean', true, true],
                    ['watch_nfc', 'nfc support', 'dukungan NFC', 'NFC support', 'Dukungan NFC', null, 'boolean', true, true],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Refrigerators
            |--------------------------------------------------------------------------
            */
            'refrigerators-freezers' => [
                'Capacity & Dimensions' => [
                    ['fridge_capacity', 'total capacity', 'kapasitas total', 'Total capacity', 'Kapasitas total', 'L', 'number', true, true],
                    ['fridge_height', 'height', 'tinggi', 'Height', 'Tinggi', 'mm', 'number', true, true],
                    ['fridge_width', 'width', 'lebar', 'Width', 'Lebar', 'mm', 'number', true, true],
                    ['fridge_depth', 'depth', 'kedalaman', 'Depth', 'Kedalaman', 'mm', 'number', true, true],
                    ['fridge_door_type', 'door type', 'jenis pintu', 'Door type', 'Jenis pintu', null, 'select', true, true],
                ],
                'Cooling Performance' => [
                    ['fridge_cooling_system', 'cooling system', 'sistem pendinginan', 'Cooling system', 'Sistem pendinginan', null, 'select', true, true],
                    ['fridge_no_frost', 'no frost', 'tanpa bunga es', 'No frost', 'Tanpa bunga es', null, 'boolean', true, true],
                    ['fridge_noise', 'noise level', 'tingkat kebisingan', 'Noise level', 'Tingkat kebisingan', 'dB', 'number', false, true],
                ],
                'Energy Efficiency' => [
                    ['fridge_energy_consumption', 'annual energy consumption', 'konsumsi energi tahunan', 'Annual energy consumption', 'Konsumsi energi tahunan', 'kWh/year', 'number', true, true],
                    ['fridge_energy_rating', 'energy rating', 'peringkat energi', 'Energy rating', 'Peringkat energi', null, 'text', true, true],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Washing Machines
            |--------------------------------------------------------------------------
            */
            'washing-machines-front-load-top-load' => [
                'Capacity & Dimensions' => [
                    ['washer_capacity', 'washing capacity', 'kapasitas cucian', 'Washing capacity', 'Kapasitas cucian', 'kg', 'number', true, true],
                    ['washer_type', 'washer type', 'jenis mesin cuci', 'Washer type', 'Jenis mesin cuci', null, 'select', true, true],
                    ['washer_spin_speed', 'maximum spin speed', 'kecepatan putaran maksimum', 'Maximum spin speed', 'Kecepatan putaran maksimum', 'rpm', 'number', true, true],
                ],
                'Washing Performance' => [
                    ['washer_programs', 'number of programs', 'jumlah program', 'Number of programs', 'Jumlah program', null, 'number', false, true],
                    ['washer_noise', 'noise level', 'tingkat kebisingan', 'Noise level', 'Tingkat kebisingan', 'dB', 'number', false, true],
                    ['washer_steam', 'steam function', 'fungsi uap', 'Steam function', 'Fungsi uap', null, 'boolean', true, true],
                ],
                'Water & Energy Efficiency' => [
                    ['washer_water_consumption', 'water consumption per cycle', 'konsumsi air per siklus', 'Water consumption per cycle', 'Konsumsi air per siklus', 'L', 'number', true, true],
                    ['washer_energy_consumption', 'energy consumption per cycle', 'konsumsi energi per siklus', 'Energy consumption per cycle', 'Konsumsi energi per siklus', 'kWh', 'number', true, true],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Car Audio
            |--------------------------------------------------------------------------
            */
            'car-audio-head-units-speakers' => [
                'Audio Performance' => [
                    ['car_audio_power', 'output power', 'daya keluaran', 'Output power', 'Daya keluaran', 'W', 'number', true, true],
                    ['car_audio_channels', 'number of channels', 'jumlah kanal', 'Number of channels', 'Jumlah kanal', null, 'number', true, true],
                    ['car_audio_frequency', 'frequency response', 'respons frekuensi', 'Frequency response', 'Respons frekuensi', 'Hz', 'text', false, true],
                ],
                'Connectivity & Compatibility' => [
                    ['car_audio_bluetooth', 'bluetooth support', 'dukungan Bluetooth', 'Bluetooth support', 'Dukungan Bluetooth', null, 'boolean', true, true],
                    ['car_audio_usb', 'usb support', 'dukungan USB', 'USB support', 'Dukungan USB', null, 'boolean', true, true],
                    ['car_audio_screen', 'touchscreen display', 'layar sentuh', 'Touchscreen display', 'Layar sentuh', null, 'boolean', true, true],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Dash Cams & GPS
            |--------------------------------------------------------------------------
            */
            'dash-cams-gps-navigation' => [
                'Recording & Navigation' => [
                    ['dashcam_resolution', 'recording resolution', 'resolusi rekaman', 'Recording resolution', 'Resolusi rekaman', null, 'text', true, true],
                    ['dashcam_frame_rate', 'frame rate', 'frame rate', 'Frame rate', 'Frame rate', 'fps', 'number', true, true],
                    ['dashcam_viewing_angle', 'viewing angle', 'sudut pandang', 'Viewing angle', 'Sudut pandang', 'degrees', 'number', true, true],
                    ['dashcam_gps', 'gps support', 'dukungan GPS', 'GPS support', 'Dukungan GPS', null, 'boolean', true, true],
                    ['dashcam_night_vision', 'night vision', 'penglihatan malam', 'Night vision', 'Penglihatan malam', null, 'boolean', true, true],
                ],
                'Storage & Connectivity' => [
                    ['dashcam_max_storage', 'maximum storage capacity', 'kapasitas penyimpanan maksimum', 'Maximum storage capacity', 'Kapasitas penyimpanan maksimum', 'GB', 'number', true, true],
                    ['dashcam_wifi', 'wifi support', 'dukungan Wi-Fi', 'Wi-Fi support', 'Dukungan Wi-Fi', null, 'boolean', true, true],
                    ['dashcam_screen_size', 'screen size', 'ukuran layar', 'Screen size', 'Ukuran layar', 'inch', 'number', true, true],
                ],
            ],
        ];

        DB::transaction(function () use ($definitions) {
            foreach ($definitions as $categorySlug => $groups) {
                $category = Category::where('slug', $categorySlug)->first();

                if (! $category) {
                    $this->command?->warn(
                        "Category tidak ditemukan: {$categorySlug}"
                    );

                    continue;
                }

                foreach ($groups as $groupName => $attributes) {
                    $group = AttributeGroup::where('category_id', $category->id)
                        ->get()
                        ->first(function (AttributeGroup $item) use ($groupName) {
                            return Str::lower(
                                trim($item->getTranslations('name')['en'] ?? '')
                            ) === Str::lower(trim($groupName));
                        });

                    if (! $group) {
                        $this->command?->warn(
                            "AttributeGroup '{$groupName}' tidak ditemukan "
                                . "pada kategori '{$categorySlug}'."
                        );

                        continue;
                    }

                    foreach ($attributes as $index => $definition) {
                        [
                            $slug,
                            $nameEn,
                            $nameId,
                            $labelEn,
                            $labelId,
                            $unit,
                            $type,
                            $isFilterable,
                            $isComparable,
                        ] = $definition;

                        $attribute = Attribute::firstOrNew([
                            'slug' => $slug,
                        ]);

                        $attribute->group_id = $group->id;

                        $attribute->name = [
                            'en' => $nameEn,
                            'id' => $nameId,
                        ];

                        $attribute->label = [
                            'en' => $labelEn,
                            'id' => $labelId,
                        ];

                        $attribute->icon_svg = null;
                        $attribute->description = null;
                        $attribute->unit = $unit;
                        $attribute->type = $type;
                        $attribute->is_filterable = $isFilterable;
                        $attribute->is_comparable = $isComparable;
                        $attribute->sort_order = $index + 1;
                        $attribute->is_active = true;

                        $attribute->save();
                    }
                }
            }
        });

        $this->command?->info('AttributeSeeder berhasil dijalankan.');
    }
}
