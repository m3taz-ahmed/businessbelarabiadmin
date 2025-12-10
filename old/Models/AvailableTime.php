<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableTime extends Model
{
    use HasFactory;

    protected $fillable = ['service_id','provider_service_id','day','from','to'];

    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }

    public function providerService()
    {
        return $this->belongsTo(ProviderService::class,'provider_service_id');
    }
}
