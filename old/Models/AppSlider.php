<?php

namespace App\Models;

use Aws\drs\drsClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'image',
        'btn',
        'link',
        'module',
        'module_id',
        'local',
    ];

    public function getImageAttribute($value)
    {
        if ($value)
            return \Storage::disk('s3')->url($value);
        else
            return null;
    }

    public function details()
    {
        return $this->hasMany(AppSliderDetail::class, 'app_slider_id');
    }

    public function scopeList($query,$lang)
    {
        return $query->where('is_active', 1 )
            ->where('local', $lang)
            ->with('details');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($model) {
            \Storage::disk('s3')->delete($model->getRawOriginal('image'));
        });
    }
}
