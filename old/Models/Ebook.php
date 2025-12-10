<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'author_id',
        'title',
        'description',
        'image',
        'published_date',
        'pages',
        'lang',
        'file',
        'audio',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_date' => 'date',
        'pages' => 'integer',
    ];

    /**
     * Get the author that owns the ebook.
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Get the categories for the ebook.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'ebook_category');
    }
    
    /**
     * Get all of the ebook's likes.
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    
    /**
     * Get all of the ebook's saves.
     */
    public function saveds()
    {
        return $this->morphMany(Save::class, 'savedable');
    }
    
    /**
     * Get all of the ebook's comments.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
    /**
     * Get all of the ebook's views.
     */
    public function views()
    {
        return $this->morphMany(View::class, 'viewable');
    }
}