<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Podcast extends Model
{
    use HasFactory;

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
        'total_plays',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_home' => 'boolean',
        'scheduled_date_time' => 'datetime',
        'deleted_at' => 'datetime',
        'duration' => 'integer',
        'total_plays' => 'integer',
    ];

    /**
     * Get the URL for the podcast's cover image.
     *
     * @param string|null $value
     * @return string|null
     */
    protected function getCoverImageAttribute($value): ?string
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http')) return $value;
        try {
            /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
            $disk = Storage::disk(config('filesystems.default'));
            return $disk->url($value);
        } catch (\Exception $e) {
            /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
            $disk = Storage::disk('public');
            return $disk->url($value);
        }
    }

    /**
     * Get the URL for the podcast's video poster.
     *
     * @param string|null $value
     * @return string|null
     */
    protected function getVideoPosterAttribute($value): ?string
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http')) return $value;
        try {
            /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
            $disk = Storage::disk(config('filesystems.default'));
            return $disk->url($value);
        } catch (\Exception $e) {
            /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
            $disk = Storage::disk('public');
            return $disk->url($value);
        }
    }

    /**
     * Get the URL for the podcast's main image.
     *
     * @param string|null $value
     * @return string|null
     */
    protected function getMainImageAttribute($value): ?string
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http')) return $value;
        try {
            /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
            $disk = Storage::disk(config('filesystems.default'));
            return $disk->url($value);
        } catch (\Exception $e) {
            /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
            $disk = Storage::disk('public');
            return $disk->url($value);
        }
    }

    /**
     * Get the URL for the podcast's video.
     *
     * @param string|null $value
     * @return string|null
     */
    protected function getVideoAttribute($value): ?string
    {
        if (!$value) return null;
        if (str_contains($value, 'buzzsprout.com') || str_starts_with($value, 'http')) return $value;
        try {
            /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
            $disk = Storage::disk(config('filesystems.default'));
            return $disk->url($value);
        } catch (\Exception $e) {
            /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
            $disk = Storage::disk('public');
            return $disk->url($value);
        }
    }

    /**
     * Get the URL for the podcast's sound file.
     *
     * @param string|null $value
     * @return string|null
     */
    protected function getSoundFileAttribute($value): ?string
    {
        if (!$value) return null;
        if (str_contains($value, 'buzzsprout.com') || str_starts_with($value, 'http')) return $value;
        try {
            /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
            $disk = Storage::disk(config('filesystems.default'));
            return $disk->url($value);
        } catch (\Exception $e) {
            /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
            $disk = Storage::disk('public');
            return $disk->url($value);
        }
    }

    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }

    protected static function booted()
    {
        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('scheduled_date_time', 'desc');
        });
    }

    public function trans()
    {
        return $this->hasMany(PodcastDetail::class, 'podcast_id', 'id');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'podcasts_articles', 'podcast_id', 'article_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'podcasts_categories', 'podcast_id', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'podcasts_tags', 'podcast_id', 'tag_id');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function benefits()
    {
        return $this->morphMany(Benefit::class, 'benefitable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function views()
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function saveds()
    {
        return $this->morphMany(Save::class, 'savedable');
    }

    public function snippets()
    {
        return $this->morphMany(Snippet::class, 'content', 'content_type', 'content_id');
    }

    public function courseContentItems()
    {
        return $this->morphMany(CourseContentItem::class, 'content');
    }

    public function guests()
    {
        return $this->belongsToMany(Guest::class, 'podcast_guests', 'podcast_id', 'guest_id');
    }

    public function isLikedByUser($userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function isSavedByUser($userId): bool
    {
        return $this->saveds()->where('user_id', $userId)->exists();
    }

    /**
     * Get the URL for the podcast's main image.
     *
     * @return string|null
     */
    public function url(): ?string
    {
        return $this->main_image ?? $this->cover_image;
    }
}
