<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class AuthorDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'author_id',
        'local',
    ];
    
    protected static function booted()
    {
        static::saving(function (AuthorDetail $authorDetail) {
            // Ensure name is not empty
            if (empty($authorDetail->name)) {
                throw ValidationException::withMessages([
                    'name' => 'The name field is required.',
                ]);
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'local', 'id');
    }
}