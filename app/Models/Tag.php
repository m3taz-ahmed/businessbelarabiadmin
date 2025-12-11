<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tags';

    protected $fillable = [
        'is_active',
        'sort',
        'category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort' => 'integer',
    ];

    protected static function booted()
    {
        static::saving(function (Tag $tag) {
            // Ensure at least one translation exists
            if ($tag->trans->count() === 0) {
                throw ValidationException::withMessages([
                    'trans' => 'At least one translation is required.',
                ]);
            }
            
            // Ensure each translation has a name
            foreach ($tag->trans as $translation) {
                if (empty($translation->name)) {
                    throw ValidationException::withMessages([
                        'trans' => 'Each translation must have a name.',
                    ]);
                }
            }
        });
    }

    public function trans()
    {
        return $this->hasMany(TagDetail::class, 'tags_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function interests()
    {
        return $this->belongsToMany(User::class, 'user_tags_interests', 'tag_id', 'user_id');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'articles_tags', 'tag_id', 'article_id');
    }
}