<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagDetail extends Model
{
    use HasFactory;

    protected $table = 'tags_details';

    protected $fillable = [
        'name',
        'tags_id',
        'local',
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tags_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'local', 'id');
    }
}
