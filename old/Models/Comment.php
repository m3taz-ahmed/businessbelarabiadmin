<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['comment' , 'commentable_id' , 'commentable_type' , 'user_id'] ;

    public function commentable()
    {
        return $this->morphTo();
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

}
