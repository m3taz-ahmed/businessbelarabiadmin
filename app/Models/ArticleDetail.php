<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class ArticleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
        'article_id',
        'local',
    ];
    
    protected static function booted()
    {
        static::saving(function (ArticleDetail $articleDetail) {
            // Ensure name is not empty
            if (empty($articleDetail->name)) {
                throw ValidationException::withMessages([
                    'name' => 'The name field is required.',
                ]);
            }
        });
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'local', 'id');
    }
}