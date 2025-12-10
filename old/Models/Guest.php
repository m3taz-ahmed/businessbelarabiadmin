<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'bio',
        'image'
    ];

    /**
     * Get the podcasts that belong to this guest.
     */
    public function podcasts()
    {
        return $this->belongsToMany(Podcast::class, 'podcast_guests', 'guest_id', 'podcast_id');
    }

    /**
     * Get the image URL attribute.
     */
    public function getImageAttribute($value)
    {
        if ($value) {
            return \Storage::disk('s3')->url($value);
        } else {
            return null;
        }
    }
}