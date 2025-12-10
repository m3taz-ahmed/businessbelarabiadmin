<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Guest;

class Podcast extends Model
{
    use HasFactory;

    // protected $table = 'podcasts';

    protected $fillable = [
        'cover_image',
        'main_image',
        'video',
        'sound_file',
        'duration',
        'is_active',
        'is_home',
        'scheduled_date_time',
        'deleted_at',
        'next_boodcast_id',
        'uuid',
        'wave',
        'video_poster',
        'buzzsprout_id',
        'buzzsprout_prsons',
        'buzzsprout_transcript',
        'buzzsprout_chapters',
        'ai_summary',
        'total_plays'
    ];

    /**
     * Get the value of coverImage
     *
     * @return  string|null  The value of coverImage
     */
    public function getCoverImageAttribute($value)
    {
        if ($value) {
            return \Storage::disk('s3')->url($value);
        } else {
            return null;
        }
    }


    public function getVideoPosterAttribute($value)
    {
        if ($value) {
            return \Storage::disk('s3')->url($value);
        } else {
            return null;
        }
    }
    /**
     * Get the value of mainImage
     *
     * @param  string  $value  The value of mainImage
     * @return string|null  The value of mainImage
     */
    public function getMainImageAttribute($value)
    {
        if ($value)
            return \Storage::disk('s3')->url($value);
        else
            return null;
    }


    /**
     * Get the value of video
     *
     * @param  string  $value  The value of mainImage
     * @return string|null  The value of mainImage
     */
    public function getVideoAttribute($value)
    {
        if ($value){
            if (strpos($value, 'buzzsprout.com') !== false) {
                return $value;
            }
            return \Storage::disk('s3')->url($value);
        }else{
            return null;
        }
    }

    // public function getWaveAttribute($value)
    // {
    //     if ($value) {
    //         return \Storage::disk('s3')->url($value);
    //     } else {
    //         return null;
    //     }
    // }



    /**
     * Get the value of sound
     *
     * @param  string  $value  The value of mainImage
     * @return string|null  The value of mainImage
     */
    public function getSoundFileAttribute($value)
    {
        if ($value){
            if (strpos($value, 'buzzsprout.com') !== false) {
                return $value;
            }
            return \Storage::disk('s3')->url($value);
        }else{
            return null;
        }
    }

    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Get all of the article's translations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

     public function trans()
     {
         return $this->hasMany(PodcastDetails::class, 'podcast_id', 'id');
     }


    public function articles()
    {
        return $this->belongsToMany(Articles::class, 'podcasts_articles', 'podcast_id', 'article_id');
    }

    /**
     * Get all of the categories for the Boodcast
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'podcasts_categories', 'podcast_id', 'category_id');
    }

    public function tags()
    {
        // return $this->belongsToMany(Tags::class , 'boodcasts_tags','boodcast_id','tag_id');
        return $this->belongsToMany(Tags::class , 'podcasts_tags','podcast_id','tag_id');
    }

    function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    function benefits()
    {
        return $this->morphMany(Benefit::class, 'benefitable');
    }

    function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    function views()
    {
        return $this->morphMany(View::class,'viewable');
    }

    function saveds(){
        return $this->morphMany(Save::class,'savedable');
    }

    protected static function booted()
    {
        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('scheduled_date_time', 'desc');
        });
    }

        public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function isSavedByUser($userId)
    {
        return $this->saveds()->where('user_id', $userId)->exists();
    }
    
    /**
     * Get all snippets for this podcast.
     */
    public function snippets()
    {
        return $this->morphMany(Snippet::class, 'content', 'content_type', 'content_id');
    }
    
    /**
     * Get all course content items related to this podcast.
     */
    public function courseContentItems()
    {
        return $this->morphMany(CourseContentItem::class, 'content');
    }
    
    /**
     * Get all guests for this podcast.
     */
    public function guests()
    {
        return $this->belongsToMany(Guest::class, 'podcast_guests', 'podcast_id', 'guest_id');
    }
}