<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'avatar',
    ];

    protected static function booted()
    {
        static::saving(function (Author $author) {
            // Ensure at least one translation exists
            if ($author->trans->count() === 0) {
                throw ValidationException::withMessages([
                    'trans' => 'At least one translation is required.',
                ]);
            }
            
            // Ensure each translation has a name
            foreach ($author->trans as $translation) {
                if (empty($translation->name)) {
                    throw ValidationException::withMessages([
                        'trans' => 'Each translation must have a name.',
                    ]);
                }
            }
        });
    }

    protected function getAvatarAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }
        
        // If it's already a full URL, return as is
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        
        // Use configured disk (public for local, s3 for production)
        try {
            /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
            $disk = Storage::disk(config('filesystems.default'));
            return $disk->url($value);
        } catch (\Exception $e) {
            // Fallback to public disk if S3 is not configured
            /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
            $disk = Storage::disk('public');
            return $disk->url($value);
        }
    }

    public function trans()
    {
        return $this->hasMany(AuthorDetail::class, 'author_id', 'id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_name', 'id');
    }
}