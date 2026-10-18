<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'category_id' => 1,
            'author_id' => 1,
            'title' => $this->faker->sentence(),
            'body' => $this->faker->paragraphs(3, true),
            'locale' => 'en',
            'slug' => $this->faker->unique()->slug(),
            'rand_id' => Str::random(10),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'published' => true,
            'is_active' => true,
            'article_tags' => ['news', 'education', 'technology', 'health'],
            'view_count' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
