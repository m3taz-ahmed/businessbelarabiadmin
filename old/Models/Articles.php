<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Articles extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $fillable = [
        'is_active',
        'is_home',
        'is_slider',
        'sort',
        'coverImage',
        'mainImage',
        'author_name',
        'schedule_publish_date',
        'uuid'
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

    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
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
     * Get all of the article's translations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function trans()
    {
        return $this->hasMany(ArticleDetail::class, 'article_id', 'id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tags::class, 'articles_tags', 'article_id', 'tag_id')->with('trans');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'articles_categories', 'article_id', 'category_id');
    }

    public function articleAudios()
    {
        return $this->hasMany(ArticlesAudio::class, 'article_id', 'id');
    }

    public function bootcasts()
    {
        return $this->belongsToMany(Boodcast::class, 'podcasts_articles',  'article_id', 'podcast_id');
    }

    function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    //benefitable
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

    function sections()
    {
        return $this->hasMany(ArticleSection::class , 'article_id');
    }

    function audios()
    {
         return $this->hasMany(ArticlesAudio::class , 'article_id');
    }

    function author(){
        return $this->belongsTo(Author::class , 'author_name' , 'id');
    }

    function saveds(){
        return $this->morphMany(Save::class,'savedable');
    }

    function summary(){
        return $this->hasMany(ArticlesSummary::class , 'article_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('schedule_publish_date', 'desc');
        });
    }

    // add a new function to check if the article is liked by the user


    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function isSavedByUser($userId)
    {
        return $this->saveds()->where('user_id', $userId)->exists();
    }

    /**
     * Get all snippets for this article.
     */
    public function snippets()
    {
        return $this->morphMany(Snippet::class, 'content', 'content_type', 'content_id');
    }
    
    /**
     * Get all course content items related to this article.
     */
    public function courseContentItems()
    {
        return $this->morphMany(CourseContentItem::class, 'content');
    }
}