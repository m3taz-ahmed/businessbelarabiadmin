<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppPage extends Model
{
    protected $fillable = ['is_active','is_faq','title','image','desc'];

    public function getImageAttribute($value)
    {
        if ($value)
            return \Storage::disk('s3')->url($value);

        else
            return null;
    }
}
