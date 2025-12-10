<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    protected $fillable = ['avatar'];

    function getAvatarAttribute($value)
    {
        if ($value)
            return \Storage::disk('s3')->url($value);
        else
            return null;
    }

    public function trans()
    {
        return $this->hasMany(AuthorDetail::class, 'author_id', 'id');
    }

    public function articles()
    {
        return $this->hasMany(Articles::class, 'author_name', 'id');
    }
}
