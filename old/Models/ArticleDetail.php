<?php

namespace App\Models;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleDetail extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'desc',
        'article_id',
        'local'
    ];

    function article(){
        return $this->belongsTo(Articles::class);
    }
    public function lang(){
        return $this->belongsTo(Language::class,'local','id');
    }
}
