<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create feedback with existing users
        $users = User::all();
        
        if ($users->count() > 0) {
            // Create feedback from existing users
            foreach ($users->take(5) as $user) {
                Feedback::factory()->create([
                    'user_id' => $user->id,
                ]);
            }
        }

        // Create some anonymous feedback
        Feedback::factory()->count(3)->anonymous()->create();

        // Create feedback with replies
        Feedback::factory()->count(2)->withReply()->create();

        // Create feedback with specific ratings
        Feedback::factory()->rating(5)->count(2)->create();
        Feedback::factory()->rating(1)->count(1)->create();
    }
}
