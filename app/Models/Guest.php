<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'bio',
        'image',
    ];

    protected function getImageAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }
        
        /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
        $disk = Storage::disk('s3');
        return $disk->url($value);
    }

    public function podcasts()
    {
        return $this->belongsToMany(Podcast::class, 'podcast_guests', 'guest_id', 'podcast_id');
    }
}
