<?php

namespace App\Models;

use App\Models\Section;
use App\Models\Articles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleSection extends Model
{
    use HasFactory;
    public $fillable = [
        'sort',
        'article_id',
        'section_id',
        'content',
        'section_name'
    ];

    public function article(){
        return $this->belongsTo(Articles::class,'article_id','id');
    }
    public function section(){
        return $this->belongsTo(Section::class,'section_id','id');
    }
    // public function getContentAttribute($value){
    //     return htmlspecialchars_decode($value);
    // }
}
