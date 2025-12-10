<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Boodcast extends Model
{
    use HasFactory, SoftDeletes;
    // protected $table = 'podcasts';

    public $fillable = [
        'title',
        'video',
        'author_id',
        'description',
        'main_image',
        'is_active',
        'is_home',
        'duration',
        'date_time',
        'next_boodcast_id',
        'deleted_at'
    ];

    /**
     * Get the value of cover photo
     *
     * @return string|null
     */
    public function getPhotoAttribute($value)
    {
        if ($value) {
            return \Storage::disk('s3')->url($value);
        } else {
            return null;
        }
    }

    /**
     * Get all of the articles for the Boodcast
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles()
    {
        return $this->belongsToMany(Articles::class, 'articles_boodcasts', 'boodcast_id', 'article_id');
    }

    /**
     * Get all of the categories for the Boodcast
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'boodcasts_categories', 'boodcast_id', 'category_id');
    }

    function tags()
    {
        return $this->belongsToMany(Tags::class , 'podcasts_tags','podcast_id','tag_id');
        // return $this->belongsToMany(Tags::class , 'boodcasts_tags','boodcast_id','tag_id');
    }
}
