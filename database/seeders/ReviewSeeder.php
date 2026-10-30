<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use App\Models\Watch;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['email' => 'sara@example.com', 'title' => 'Toy Story', 'rating' => 5, 'comment' => 'Perfect for family night. The kids loved it.'],
            ['email' => 'sara@example.com', 'title' => 'Paddington 2', 'rating' => 5, 'comment' => 'Funny and kind. Nothing to worry about.'],
            ['email' => 'sara@example.com', 'title' => 'Titanic', 'rating' => 4, 'comment' => 'We watched the family edit and it was fine.'],
            ['email' => 'karwan@example.com', 'title' => 'Toy Story', 'rating' => 4, 'comment' => 'Still a classic.'],
            ['email' => 'karwan@example.com', 'title' => 'Interstellar', 'rating' => 5, 'comment' => 'Amazing story, a bit long for small kids.'],
            ['email' => 'karwan@example.com', 'title' => 'The Martian', 'rating' => 4, 'comment' => 'The TV edit removes the bad language.'],
            ['email' => 'lana@example.com', 'title' => 'Up', 'rating' => 5, 'comment' => 'The first ten minutes made everyone cry.'],
            ['email' => 'lana@example.com', 'title' => 'Inception', 'rating' => 4, 'comment' => 'Some action scenes, but fine for teenagers.'],
        ] as $sample) {
            $user = User::where('email', $sample['email'])->firstOrFail();
            $movie = Movie::where('title', $sample['title'])->firstOrFail();

            // A review comes after watching, so mark the movie as watched first.
            Watch::firstOrCreate(
                ['user_id' => $user->id, 'movie_id' => $movie->id],
                ['watched_at' => now()],
            );

            $review = Review::firstOrNew(['user_id' => $user->id, 'movie_id' => $movie->id]);
            $review->rating = $sample['rating'];
            $review->comment = $sample['comment'];
            $review->save();
        }
    }
}
