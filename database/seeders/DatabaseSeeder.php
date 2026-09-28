<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create 3 different users
        $users = User::factory(3)->create();

        // 2. Create 20 posts distributed randomly among those 3 users
        $posts = Post::factory(20)->create([
            'user_id' => fn() => $users->random()->id,
        ]);

        // 3. For each post, attach 1-3 media slides (mostly images, some video/text)
        //    and 0-5 comments from random users
        foreach ($posts as $post) {
            $mediaCount = rand(1, 3);

            for ($order = 0; $order < $mediaCount; $order++) {
                $type = fake()->randomElement(['image', 'image', 'image', 'video', 'text']);

                $factory = PostMedia::factory()->state([
                    'post_id' => $post->id,
                    'order' => $order,
                ]);

                match ($type) {
                    'video' => $factory->video()->create(),
                    'text' => $factory->text()->create(),
                    default => $factory->create(),
                };
            }

            Comment::factory(rand(0, 5))->create([
                'post_id' => $post->id,
                'user_id' => fn() => $users->random()->id,
            ]);
        }
    }
}
