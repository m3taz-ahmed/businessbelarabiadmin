<?php

namespace App\Models;

use App\Models\ArticleSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory , SoftDeletes;
    public $fillable = ['name','photo','blade_name','is_active'];

    public function getCoverPhotoAttribute($value)
    {
        if ($value)
            return \Storage::disk('s3')->url($value);
        else
            return null;
    }


    public function articleSection(){

        return $this->hasMany(ArticleSection::class,'section_id','id');
    }

}
