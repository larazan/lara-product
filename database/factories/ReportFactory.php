<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
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
            'reportable_type' => 'App\Models\Product',
            'reportable_id' => Arr::random([1, 2, 3, 4, 5]),
            'reason' => $this->faker->sentence(),
            'message' => $this->faker->words(10, true),
            'user_id' => User::factory(),
        ];
    }
}
