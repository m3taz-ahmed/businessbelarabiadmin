<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotefiedContent extends Model
{
    use HasFactory;
    protected $table = 'notefied_content';
    protected $fillable = [
        'content_id',
        'content_type',
    ];
}
