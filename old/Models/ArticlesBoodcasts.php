<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticlesBoodcasts extends Model
{
    use HasFactory;
    public $fillable = [
        'article_id',
        'boodcast_id'
    ];
}
