<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'client_id', 'notes', 'hourly_rate','prioroty','period_from','total_time','ended_at','order','selected'];

    function client(){
        return $this->belongsTo(Client::class);
    }

    /*function times(){
        return $this->hasMany(timeTable::class);
    }*/

    protected function hourlyRate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }
}
