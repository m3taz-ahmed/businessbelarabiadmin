<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoodcastsCategories extends Model
{
    use HasFactory;
    public $fillable = [
        'boodcast_id',
        'category_id'
    ];
}
