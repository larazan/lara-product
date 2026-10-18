<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faqs = [
            [
                'question' => [
                    'en' => 'What is this quiz platform?',
                    'id' => 'Apa itu platform kuis ini?'
                ],
                'answer' => [
                    'en' => 'This platform allows users to create, share, and take quizzes on various topics such as education, entertainment, and personality tests.',
                    'id' => 'Platform ini memungkinkan pengguna membuat, membagikan, dan mengikuti kuis tentang berbagai topik seperti pendidikan, hiburan, dan tes kepribadian.'
                ],
                'order_position' => 1,
                'is_active' => true,
            ],
            [
                'question' => [
                    'en' => 'Do I need an account to take a quiz?',
                    'id' => 'Apakah saya perlu akun untuk mengikuti kuis?'
                ],
                'answer' => [
                    'en' => 'Some quizzes can be taken without an account, but creating an account allows you to save results and track your progress.',
                    'id' => 'Beberapa kuis dapat diikuti tanpa akun, namun dengan membuat akun Anda dapat menyimpan hasil dan melacak progres.'
                ],
                'order_position' => 2,
                'is_active' => true,
            ],
            [
                'question' => [
                    'en' => 'How do I create my own quiz?',
                    'id' => 'Bagaimana cara membuat kuis saya sendiri?'
                ],
                'answer' => [
                    'en' => 'After signing in, go to the dashboard and click "Create Quiz". You can add questions, options, images, and customize the quiz settings.',
                    'id' => 'Setelah masuk, buka dashboard dan klik "Buat Kuis". Anda dapat menambahkan pertanyaan, opsi, gambar, dan menyesuaikan pengaturan kuis.'
                ],
                'order_position' => 3,
                'is_active' => true,
            ],
            [
                'question' => [
                    'en' => 'What is a premium quiz?',
                    'id' => 'Apa itu kuis premium?'
                ],
                'answer' => [
                    'en' => 'Premium quizzes are advanced quizzes that require a premium subscription to access all features and content.',
                    'id' => 'Kuis premium adalah kuis lanjutan yang memerlukan langganan premium untuk mengakses semua fitur dan kontennya.'
                ],
                'order_position' => 4,
                'is_active' => true,
            ],
            [
                'question' => [
                    'en' => 'What benefits do premium members get?',
                    'id' => 'Apa keuntungan menjadi anggota premium?'
                ],
                'answer' => [
                    'en' => 'Premium members can access unlimited quizzes, advanced analytics, private quizzes, advanced question banks, and remove advertisements.',
                    'id' => 'Anggota premium dapat mengakses kuis tanpa batas, analitik lanjutan, kuis privat, bank soal lanjutan, dan menghapus iklan.'
                ],
                'order_position' => 5,
                'is_active' => true,
            ],
            [
                'question' => [
                    'en' => 'Can I share my quiz with others?',
                    'id' => 'Apakah saya bisa membagikan kuis saya kepada orang lain?'
                ],
                'answer' => [
                    'en' => 'Yes, every quiz has a unique link that you can share with friends, students, or colleagues.',
                    'id' => 'Ya, setiap kuis memiliki tautan unik yang dapat Anda bagikan kepada teman, siswa, atau rekan kerja.'
                ],
                'order_position' => 6,
                'is_active' => true,
            ],
            [
                'question' => [
                    'en' => 'Can I make a private quiz?',
                    'id' => 'Apakah saya bisa membuat kuis privat?'
                ],
                'answer' => [
                    'en' => 'Yes, premium users can create private quizzes that are accessible only via a special link.',
                    'id' => 'Ya, pengguna premium dapat membuat kuis privat yang hanya dapat diakses melalui tautan khusus.'
                ],
                'order_position' => 7,
                'is_active' => true,
            ],
            [
                'question' => [
                    'en' => 'How are quiz scores calculated?',
                    'id' => 'Bagaimana skor kuis dihitung?'
                ],
                'answer' => [
                    'en' => 'Scores are calculated based on correct answers and the scoring settings defined by the quiz creator.',
                    'id' => 'Skor dihitung berdasarkan jawaban yang benar dan pengaturan penilaian yang ditentukan oleh pembuat kuis.'
                ],
                'order_position' => 8,
                'is_active' => true,
            ],
            [
                'question' => [
                    'en' => 'Can I view analytics for my quizzes?',
                    'id' => 'Apakah saya dapat melihat analitik untuk kuis saya?'
                ],
                'answer' => [
                    'en' => 'Yes, quiz creators can view analytics such as participant counts, average scores, and performance insights.',
                    'id' => 'Ya, pembuat kuis dapat melihat analitik seperti jumlah peserta, rata-rata skor, dan wawasan performa.'
                ],
                'order_position' => 9,
                'is_active' => true,
            ],
            [
                'question' => [
                    'en' => 'How long does premium access last?',
                    'id' => 'Berapa lama akses premium berlaku?'
                ],
                'answer' => [
                    'en' => 'Premium access lasts for 12 months from the date of purchase.',
                    'id' => 'Akses premium berlaku selama 12 bulan sejak tanggal pembelian.'
                ],
                'order_position' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faqData) {
            Faq::create($faqData);
            // The slug will be automatically generated by the HasSlug trait
        }
    }
}
