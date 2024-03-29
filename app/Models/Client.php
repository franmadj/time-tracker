<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'currency', 'hourly_rate', 'color'];

    function user(){
        return $this->belongsTo(User::class);
    }

    function projects(){
        return $this->hasMany(Project::class);
    }
}
