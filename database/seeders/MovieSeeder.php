<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['title' => 'Toy Story', 'genre' => 'Animation', 'release_year' => 1995, 'nudity_level' => 'none', 'violence_level' => 'mild', 'language_level' => 'none'],
            ['title' => 'The Lion King', 'genre' => 'Animation', 'release_year' => 1994, 'nudity_level' => 'none', 'violence_level' => 'mild', 'language_level' => 'none'],
            ['title' => 'Finding Nemo', 'genre' => 'Animation', 'release_year' => 2003, 'nudity_level' => 'none', 'violence_level' => 'mild', 'language_level' => 'none'],
            ['title' => 'Up', 'genre' => 'Animation', 'release_year' => 2009, 'nudity_level' => 'none', 'violence_level' => 'mild', 'language_level' => 'none'],
            ['title' => 'Spider-Man: Into the Spider-Verse', 'genre' => 'Animation', 'release_year' => 2018, 'nudity_level' => 'none', 'violence_level' => 'moderate', 'language_level' => 'mild'],
            ['title' => 'Paddington 2', 'genre' => 'Family', 'release_year' => 2017, 'nudity_level' => 'none', 'violence_level' => 'mild', 'language_level' => 'none'],
            ['title' => "Harry Potter and the Sorcerer's Stone", 'genre' => 'Fantasy', 'release_year' => 2001, 'nudity_level' => 'none', 'violence_level' => 'mild', 'language_level' => 'mild'],
            ['title' => 'The Lord of the Rings: The Fellowship of the Ring', 'genre' => 'Fantasy', 'release_year' => 2001, 'nudity_level' => 'none', 'violence_level' => 'moderate', 'language_level' => 'none'],
            ['title' => 'Inception', 'genre' => 'Sci-Fi', 'release_year' => 2010, 'nudity_level' => 'none', 'violence_level' => 'moderate', 'language_level' => 'mild'],
            ['title' => 'Interstellar', 'genre' => 'Sci-Fi', 'release_year' => 2014, 'nudity_level' => 'none', 'violence_level' => 'mild', 'language_level' => 'mild'],
            ['title' => 'The Martian', 'genre' => 'Sci-Fi', 'release_year' => 2015, 'nudity_level' => 'mild', 'violence_level' => 'mild', 'language_level' => 'severe',
                'has_clean_edit' => true, 'clean_edit_notes' => 'Strong language is muted in the TV broadcast edit.'],
            ['title' => 'The Matrix', 'genre' => 'Sci-Fi', 'release_year' => 1999, 'nudity_level' => 'none', 'violence_level' => 'severe', 'language_level' => 'moderate'],
            ['title' => 'Titanic', 'genre' => 'Drama', 'release_year' => 1997, 'nudity_level' => 'severe', 'violence_level' => 'moderate', 'language_level' => 'moderate',
                'has_clean_edit' => true, 'clean_edit_notes' => 'The drawing scene and the car scene are trimmed in the family edit.'],
            ['title' => 'Forrest Gump', 'genre' => 'Drama', 'release_year' => 1994, 'nudity_level' => 'mild', 'violence_level' => 'moderate', 'language_level' => 'moderate',
                'has_clean_edit' => true, 'clean_edit_notes' => 'Strong language is muted and a short war scene is shortened.'],
            ['title' => 'Gladiator', 'genre' => 'Action', 'release_year' => 2000, 'nudity_level' => 'none', 'violence_level' => 'severe', 'language_level' => 'mild'],
            ['title' => 'The Dark Knight', 'genre' => 'Action', 'release_year' => 2008, 'nudity_level' => 'none', 'violence_level' => 'moderate', 'language_level' => 'mild'],
        ] as $sample) {
            $movie = Movie::firstOrNew(['title' => $sample['title']]);
            $movie->fill([
                'has_clean_edit' => false,
                'clean_edit_notes' => null,
                ...$sample,
            ]);
            $movie->save();
        }
    }
}
