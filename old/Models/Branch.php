<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['provider_id','is_active','area_id','latitude','longitude'];


    public function area()
    {
        return $this->belongsTo(Area::class,'area_id','id');
    }

    public function translation()
    {
        return $this->hasOne(BranchDetail::class,'branch_id','id');
    }

    public function services()
    {
        return $this->hasMany(Service::class,'branch_id');
    }

    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class,'provider_id');
    }

}
