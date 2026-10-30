<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'genre' => $this->genre,
            'release_year' => $this->release_year,
            'nudity_level' => $this->nudity_level,
            'violence_level' => $this->violence_level,
            'language_level' => $this->language_level,
            'has_clean_edit' => $this->has_clean_edit,
            'clean_edit_notes' => $this->clean_edit_notes,
            'is_family_clean' => $this->isFamilyClean(),
            'average_rating' => $this->whenHas('reviews_avg_rating', fn ($average) => $average === null ? null : round((float) $average, 1)),
            'reviews_count' => $this->whenCounted('reviews'),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}
