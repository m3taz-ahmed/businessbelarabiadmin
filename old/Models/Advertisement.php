<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;
    protected $fillable = ['adv_file', 'adv_space', 'start_date', 'end_date', 'link'];


    public function setAdvFileAttribute($image)
    {
        if ($image) {
            $this->attributes['adv_file'] = \Storage::disk('s3')->put('businessblarabi/advertisements/files', $image);
        }
    }

    public function getAdvFileAttribute($value)
    {
        if ($value)
            return \Storage::disk('s3')->url($value);

        else
            return null;
    }
}
