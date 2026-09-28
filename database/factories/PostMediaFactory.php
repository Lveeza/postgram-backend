<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PostMedia>
 */
class PostMediaFactory extends Factory
{
    /**
     * Default state: an image slide.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'type' => 'image',
            'path' => 'https://picsum.photos/seed/' . fake()->unique()->numberBetween(1, 1000000) . '/800/800',
            'order' => 0,
        ];
    }

    /**
     * State for a video slide. Uses a small public sample video.
     */
    public function video(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'video',
            'path' => 'https://www.w3schools.com/html/mov_bbb.mp4',
        ]);
    }

    /**
     * State for a text-only slide.
     */
    public function text(): static
    {
        $lines = [
            'Some days you just have to trust the process.',
            'Small wins count too.',
            'Grateful for this chapter.',
            'Still figuring it out, one day at a time.',
            'Good things take time.',
        ];

        return $this->state(fn(array $attributes) => [
            'type' => 'text',
            'path' => fake()->randomElement($lines),
        ]);
    }
}
