<?php

namespace Database\Seeders\Models;

use App\Models\Comment;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = Profile::all();
        $users = User::all();
        $profiles->each(function (Profile $profile) use ($users) {
            Comment::create([
                'profile_id' => $profile->id,
                'comment' => fake()->sentence(),
                'user_id' => $users->random()->id,
            ]);
        });
    }
}
