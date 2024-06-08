<?php

namespace Database\Factories;

use App\Models\Job;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->name,
            'job_type_id' => rand(1, 5),
            'category_id' => rand(1, 5),
            'user_id' => 4,
            'salary' => rand(10, 90) . "k",
            'benefits' => $this->faker->text(50),
            'responsibility' => $this->faker->text(50),
            'qualifications' => $this->faker->text(50),
            'keywords' => implode(' ', $this->faker->words(4)),
            'vacancy' => rand(1, 5),
            'location' => $this->faker->city,
            'description' => $this->faker->text,
            'experience' => rand(1, 10),
            'company_name' => $this->faker->name,
            'company_location' => $this->faker->address,
            'company_website' => $this->faker->url,
        ];
    }
}
