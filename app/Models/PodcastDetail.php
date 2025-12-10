<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
        'podcast_id',
        'local',
    ];

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'local', 'id');
    }
}
