<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'benefitable_id',
        'benefitable_type',
        'benefit_text',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function benefitable()
    {
        return $this->morphTo();
    }
}
