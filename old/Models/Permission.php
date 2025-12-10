<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable  = ['name','permission_type_id','object_id'];

    public function rolePermission(){
        return $this->hasMany(RolePermission::class);
    }


    public function hasRole($id){
        $isexist=$this->hasMany(RolePermission::class)->where('role_id',$id)->first();
        if(isset($isexist))
        {
            return true;
        }
        return false;
    }

}
