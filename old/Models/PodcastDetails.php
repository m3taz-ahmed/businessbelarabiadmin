<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'desc', 'podcast_id',
        'local'
    ];

    function podcast(){
        return $this->belongsTo(Podcast::class);
    }
}
