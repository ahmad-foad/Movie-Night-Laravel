<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'genre',
    'release_year',
    'nudity_level',
    'violence_level',
    'language_level',
    'has_clean_edit',
    'clean_edit_notes',
])]
class Movie extends Model
{
    // Content levels a movie can have, from cleanest to strongest.
    public const LEVELS = ['none', 'mild', 'moderate', 'severe'];

    // Levels that are fine for a family movie night.
    public const CLEAN_LEVELS = ['none', 'mild'];

    protected function casts(): array
    {
        return [
            'release_year' => 'integer',
            'has_clean_edit' => 'boolean',
        ];
    }

    public function watches(): HasMany
    {
        return $this->hasMany(Watch::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // A movie is family clean when all three flags are none or mild,
    // or when a clean edit (trimmed or muted version) is available.
    public function isFamilyClean(): bool
    {
        if ($this->has_clean_edit) {
            return true;
        }

        return in_array($this->nudity_level, self::CLEAN_LEVELS)
            && in_array($this->violence_level, self::CLEAN_LEVELS)
            && in_array($this->language_level, self::CLEAN_LEVELS);
    }

    // The same rule as isFamilyClean(), written as a database query for ?clean=true.
    #[Scope]
    protected function familyClean(Builder $query): void
    {
        $query->where(function (Builder $query) {
            $query->where(function (Builder $query) {
                $query->whereIn('nudity_level', self::CLEAN_LEVELS)
                    ->whereIn('violence_level', self::CLEAN_LEVELS)
                    ->whereIn('language_level', self::CLEAN_LEVELS);
            })->orWhere('has_clean_edit', true);
        });
    }
}
