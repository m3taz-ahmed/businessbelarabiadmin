<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'local',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'local', 'id');
    }
}
