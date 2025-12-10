<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;
    protected $fillable = ['image', 'desc', 'facebook_link', 'tiktok_link', 'twitter_link', 'linkedin_link', 'youtube_link'];


    public function setImageAttribute($image)
    {
        if ($image) {
            $this->attributes['image'] = \Storage::disk('s3')->put('businessblarabi/aboutus/files', $image);
        }
    }

    public function getImageAttribute($value)
    {
        if ($value)
            return \Storage::disk('s3')->url($value);

        else
            return null;
    }
}
