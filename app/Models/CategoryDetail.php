<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CategoryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
        'local',
        'category_id',
    ];
    
    protected static function booted()
    {
        static::saving(function (CategoryDetail $categoryDetail) {
            // Ensure name is not empty
            if (empty($categoryDetail->name)) {
                throw ValidationException::withMessages([
                    'name' => 'The name field is required.',
                ]);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'local', 'id');
    }
}