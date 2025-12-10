<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticlesTags extends Model
{
    use HasFactory;
    public $fillable = [
        'article_id',
        'tag_id'
    ];
}
