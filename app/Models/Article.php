<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

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
        'coverImage',
        'mainImage',
        'content', // Added content field
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_home' => 'boolean',
        'is_slider' => 'boolean',
        'sort' => 'integer',
        'schedule_publish_date' => 'datetime',
        'content' => 'array', // Cast content as array
    ];

    protected static function booted()
    {
        static::saving(function (Article $article) {
            // Ensure at least one translation exists
            if ($article->trans->count() === 0) {
                throw ValidationException::withMessages([
                    'trans' => 'At least one translation is required.',
                ]);
            }
            
            // Ensure each translation has a name
            foreach ($article->trans as $translation) {
                if (empty($translation->name)) {
                    throw ValidationException::withMessages([
                        'trans' => 'Each translation must have a name.',
                    ]);
                }
            }
        });
    }

    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }

    protected static function boot()
    {
        parent::boot();

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

    /**
     * Get the article content, either from the content field or from article_sections
     */
    public function getContentAttribute($value)
    {
        // If content field has data, return it
        if (!is_null($value)) {
            return json_decode($value, true);
        }

        // If content field is empty, get data from article_sections
        $sections = $this->sections()->orderBy('sort')->get();
        
        if ($sections->isEmpty()) {
            return [];
        }

        $content = [];
        foreach ($sections as $section) {
            $type = $section->section_name;
            $data = json_decode($section->content, true);

            // Map legacy types to new Builder types
            if ($type === 'paragraph') {
                $type = 'paragraph_text';
            }

            // Fix 'images' block structure: wrap list of items into 'images' key for Repeater
            if ($type === 'images' && is_array($data) && isset($data[0])) {
                $data = ['images' => $data];
            }

            $content[] = [
                'type' => $type,
                'data' => $data,
            ];
        }

        return $content;
    }

    /**
     * Set the article content and save it to the content field
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = is_array($value) ? json_encode($value) : $value;
    }
}