<?php

namespace App\Models;

use App\Models\ArticlesAudioDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticlesAudio extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'articles_audio';
    public $fillable = [
        'is_active',
        'path',
        'article_id',
        'title',
        'sort'
    ];

    public function getPathAttribute($value)
    {
        if ($value) {

            return \Storage::disk('s3')->url($value);
        } else {

            return null;
        }
    }
}
