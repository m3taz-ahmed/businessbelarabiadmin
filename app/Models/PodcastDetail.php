<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class PodcastDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
        'podcast_id',
        'local',
    ];
    
    protected static function booted()
    {
        static::saving(function (PodcastDetail $podcastDetail) {
            // Ensure name is not empty
            if (empty($podcastDetail->name)) {
                throw ValidationException::withMessages([
                    'name' => 'The name field is required.',
                ]);
            }
        });
    }

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'local', 'id');
    }
}