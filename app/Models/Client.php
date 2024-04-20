<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'currency', 'hourly_rate', 'color', 'prioroty', 'period_from', 'total_time', 'active_projects', 'order', 'slug'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    protected function hourlyRate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value / 100,
            set: fn($value) => $value * 100
        );
    }

    public function setTotalTime()
    {
        $totalTime = 0;
        $this->projects->each(function ($item) use (&$totalTime) {
            $totalTime += $item->total_time;
        });
        $this->update(['total_time' => $totalTime]);
    }
}
