<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleObject extends Model
{
    protected $table = 'objects' ;
    use HasFactory;

    protected $fillable = ['name','permission_type_id','object_id'];
}
