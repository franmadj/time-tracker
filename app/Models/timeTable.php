<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class timeTable extends Model
{

    protected $fillable = ['started_at','ended_at'];

    use HasFactory;
}
