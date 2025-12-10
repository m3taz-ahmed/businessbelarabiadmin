<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticlesSummary extends Model
{
    use HasFactory;
    public $fillable = [
        'article_id',
        'local',
        'summary'
    ];
    function article(){
        return $this->belongsTo(Articles::class);
    }
    public function lang(){
        return $this->belongsTo(Language::class,'local','id');
    }
}
