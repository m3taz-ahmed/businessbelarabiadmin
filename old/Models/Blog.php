<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title','image','desc','content','meta_title','meta_desc','meta_keywords','is_active','local'];

    public function getImageAttribute($value)
    {
        if ($value)
            return \Storage::disk('s3')->url($value);

        else
            return null;
    }
}
