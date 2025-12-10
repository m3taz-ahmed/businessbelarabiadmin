<?php

namespace App\Models;

use App\Models\CategoryDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['is_active', 'image' ,'order' , 'is_home', 'category_id', 'show_in_tab'];
    public function getImageAttribute($value)
    {
        if ($value)
            return \Storage::disk('s3')->url($value);
        else
            return null;
    }

    public function scopeList($query,$lang)
    {
        return $query->where('is_active', 1 )
            ->join('category_details', 'categories.id', 'category_details.category_id')
            ->where('category_details.local', $lang)
            ->orderBy('order')
            ->select('categories.*', 'category_details.name', 'category_details.desc');
    }
    public function trans(){
        return $this->hasMany(CategoryDetail::class);
    }

    function articles(){
        return $this->belongsToMany(Articles::class , 'articles_categories' , 'category_id','article_id');
    }

    function podcasts(){
        return $this->belongsToMany(Podcast::class ,  'podcasts_categories', 'category_id', 'podcast_id');
    }

    public function interests(){
        return $this->belongsToMany(User::class , 'user_categories_interests' , 'category_id' , 'user_id');
    }
}
