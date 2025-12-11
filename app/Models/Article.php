<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'articles';

    protected $fillable = [
        'is_active',
        'is_home',
        'is_slider',
        'sort',
        'author_name',
        'schedule_publish_date',
        'uuid',
        'cover_image',
        'main_image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_home' => 'boolean',
        'is_slider' => 'boolean',
        'sort' => 'integer',
        'schedule_publish_date' => 'datetime',
    ];

    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }

    protected static function booted()
    {
        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('schedule_publish_date', 'desc');
        });
    }

    public function trans()
    {
        return $this->hasMany(ArticleDetail::class, 'article_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'articles_tags', 'article_id', 'tag_id')->with('trans');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'articles_categories', 'article_id', 'category_id');
    }

    public function articleAudios()
    {
        return $this->hasMany(ArticleAudio::class, 'article_id', 'id');
    }

    public function audios()
    {
        return $this->hasMany(ArticleAudio::class, 'article_id');
    }

    public function podcasts()
    {
        return $this->belongsToMany(Podcast::class, 'podcasts_articles', 'article_id', 'podcast_id');
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

    public function sections()
    {
        return $this->hasMany(ArticleSection::class, 'article_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_name', 'id');
    }

    public function saveds()
    {
        return $this->morphMany(Save::class, 'savedable');
    }

    public function summary()
    {
        return $this->hasMany(ArticleSummary::class, 'article_id');
    }

    public function snippets()
    {
        return $this->morphMany(Snippet::class, 'content', 'content_type', 'content_id');
    }

    public function courseContentItems()
    {
        return $this->morphMany(CourseContentItem::class, 'content');
    }

    public function isLikedByUser($userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function isSavedByUser($userId): bool
    {
        return $this->saveds()->where('user_id', $userId)->exists();
    }
}
