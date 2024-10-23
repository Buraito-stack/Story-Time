<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoryFactory extends Factory
{
    protected $model = Story::class;

    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence,
            'author_id'   => User::factory(), 
            'category_id' => Category::inRandomOrder()->first()->id, 
            'content'     => $this->generateLongContent(),
            'views'       => $this->faker->numberBetween(0, 1000), 
        ];
    }

    private function generateLongContent(): string
    {
        return collect($this->faker->paragraphs(50))->implode("\n\n");
    }
}
