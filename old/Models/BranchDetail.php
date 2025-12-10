<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchDetail extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id','local','name','address'];
}
