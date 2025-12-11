<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class TagDetail extends Model
{
    use HasFactory;

    protected $table = 'tags_details';

    protected $fillable = [
        'name',
        'tags_id',
        'local',
    ];
    
    protected static function booted()
    {
        static::saving(function (TagDetail $tagDetail) {
            // Ensure name is not empty
            if (empty($tagDetail->name)) {
                throw ValidationException::withMessages([
                    'name' => 'The name field is required.',
                ]);
            }
        });
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tags_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'local', 'id');
    }
}