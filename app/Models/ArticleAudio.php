<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ArticleAudio extends Model
{
    use HasFactory;

    protected $table = 'articles_audios';

    protected $fillable = [
        'article_id',
        'audio_file',
        'duration',
        'language_id',
    ];

    protected $casts = [
        'duration' => 'integer',
    ];

    protected function getAudioFileAttribute($value): ?string
    {
        return $value ? Storage::disk('s3')->url($value) : null;
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
