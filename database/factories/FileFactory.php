<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition(): array
    {
        return [
            'file_name' => $this->faker->word . '.jpg',
            'file_path' => $this->faker->imageUrl(),
            'file_size' => $this->faker->numberBetween(1024, 2048), 
            'file_type' => 'image/jpeg',
        ];
    }
}
