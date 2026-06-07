<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

trait HasCardImages
{
    public function getMainImageUrlAttribute(): string
    {
        $image = $this->resolveMainImagePath();

        if (! $image) {
            return asset('img/empty.png');
        }

        return $this->imageUrl($image);
    }

    public function getMainImageAttribute(): string
    {
        return $this->resolveMainImagePath() ?: 'img/empty.png';
    }

    public function imageUrl(?string $image): string
    {
        if (! $this->isUsableImagePath($image)) {
            return asset('img/empty.png');
        }

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        if (str_starts_with($image, 'storage/')) {
            return asset($image);
        }

        if (file_exists(public_path($image))) {
            return asset($image);
        }

        return asset('storage/'.ltrim($image, '/'));
    }

    private function resolveMainImagePath(): ?string
    {
        $candidates = [];
        $mainImage = $this->attributes['main_image'] ?? null;

        if ($this->isUsableImagePath($mainImage)) {
            $candidates[] = $mainImage;
        }

        if (is_array($this->gallery)) {
            foreach ($this->gallery as $image) {
                if ($this->isUsableImagePath($image)) {
                    $candidates[] = $image;
                }
            }
        }

        foreach (array_unique($candidates) as $image) {
            if ($this->imagePathExists($image)) {
                return $image;
            }
        }

        return $candidates[0] ?? null;
    }

    private function isUsableImagePath(mixed $image): bool
    {
        return is_string($image) && $image !== '' && $image !== 'img/empty.png';
    }

    private function imagePathExists(string $image): bool
    {
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return true;
        }

        if (str_starts_with($image, 'storage/')) {
            return file_exists(public_path($image));
        }

        if (file_exists(public_path($image))) {
            return true;
        }

        return Storage::disk('public')->exists(ltrim($image, '/'));
    }
}
