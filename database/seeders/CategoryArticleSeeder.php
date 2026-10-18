<?php

namespace Database\Seeders;

use App\Models\CategoryArticle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CategoryArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'name' => 'News',
                'slug' => 'news',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Info',
                'slug' => 'info',
                'created_at' => Carbon::now(),
            ],
        ];

        CategoryArticle::insert($data);
    }
}
