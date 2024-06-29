<?php

namespace Database\Factories\EcomDemo\Files\Entities;

use EcomDemo\Files\Entities\File;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<File>
 */
class FileFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = File::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'path' => fake()->filePath(),
            'size' => fake()->randomNumber() . 'KB',
            'type' => fake()->fileExtension()
        ];
    }
}
