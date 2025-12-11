<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'section_name',
        'content',
        'sort',
    ];

    protected $casts = [
        'sort' => 'integer',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
