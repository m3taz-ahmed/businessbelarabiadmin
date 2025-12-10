<?php

namespace App\Models;


use App\Models\TagsDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tags extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $fillable = ['is_active','sort' , 'category_id'];

    public function trans(){
        return $this->hasMany(TagsDetail::class , 'tags_id','id');
    }

    public function interests(){
        return $this->belongsToMany(User::class , 'user_tags_interests' , 'tag_id' , 'user_id');
    }
}
