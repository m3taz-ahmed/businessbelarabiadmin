<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleSummary extends Model
{
    use HasFactory;

    protected $table = 'articles_summaries';

    protected $fillable = [
        'article_id',
        'summary',
        'local',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'local', 'id');
    }
}
