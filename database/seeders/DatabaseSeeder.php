<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Contact;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(30)->create();
        Contact::factory(40)->create();
        Report::factory(20)->create();
        Article::factory(30)->create();

        $this->call([
            CompanySeeder::class,
            BrandSeeder::class,
            CountrySeeder::class,
            FaqSeeder::class,
            CategoryArticleSeeder::class,
            CategorySeeder::class,
            AttributeGroupSeeder::class,
            AttributeSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
