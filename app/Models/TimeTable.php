<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTable extends Model
{

    protected $fillable = ['started_at','ended_at'];

    use HasFactory;

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
