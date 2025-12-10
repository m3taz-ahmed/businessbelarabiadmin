<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'image',
        'order',
        'is_home',
        'category_id',
        'show_in_tab',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_home' => 'boolean',
        'show_in_tab' => 'boolean',
        'order' => 'integer',
    ];

    protected function getImageAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }
        
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        
        try {
            return Storage::disk(config('filesystems.default'))->url($value);
        } catch (\Exception $e) {
            return Storage::disk('public')->url($value);
        }
    }

    public function scopeList($query, $lang)
    {
        return $query->where('is_active', 1)
            ->join('category_details', 'categories.id', 'category_details.category_id')
            ->where('category_details.local', $lang)
            ->orderBy('order')
            ->select('categories.*', 'category_details.name', 'category_details.desc');
    }

    public function trans()
    {
        return $this->hasMany(CategoryDetail::class);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'articles_categories', 'category_id', 'article_id');
    }

    public function podcasts()
    {
        return $this->belongsToMany(Podcast::class, 'podcasts_categories', 'category_id', 'podcast_id');
    }

    public function interests()
    {
        return $this->belongsToMany(User::class, 'user_categories_interests', 'category_id', 'user_id');
    }
}
