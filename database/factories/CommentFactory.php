<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $comments = [
            'This is amazing!',
            'Love this so much.',
            'Where was this taken?',
            'Absolutely stunning.',
            'Needed to see this today.',
            'So proud of you!',
            'This made my day.',
            'Incredible shot.',
            'Can relate to this so much.',
            'Wow, just wow.',
            'Saving this for later.',
            'You always post the best content.',
            'This is so relatable.',
            'Obsessed with this.',
            'Take me there right now.',
        ];

        return [
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'content' => fake()->randomElement($comments),
        ];
    }
}
