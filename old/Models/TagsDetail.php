<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagsDetail extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'tags_id',
        'local'
    ];
}
