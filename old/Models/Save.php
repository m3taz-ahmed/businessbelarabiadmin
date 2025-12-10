<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Save extends Model
{
    protected $table = 'saved';
    protected $fillable = ['user_id' , 'savedable_id' , 'savedable_type'];
    use HasFactory;

    function savedable(){
        return $this->morphTo();
    }


}
