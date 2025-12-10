<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicDegree extends Model
{
    use HasFactory;
    protected $fillable = [] ;

    public function scopeList($query,$lang)
    {
        // return $query->where('is_active', 1 )
        //     ->join('category_details', 'categories.id', 'category_details.category_id')
        //     ->where('category_details.local', $lang)
        //     ->orderBy('order')
        //     ->select('categories.*', 'category_details.name', 'category_details.desc');
    }
}
