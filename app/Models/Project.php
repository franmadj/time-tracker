<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'client_id', 'notes', 'hourly_rate', 'hourly_rate_two', 'prioroty', 'period_from', 'total_time', 'ended_at', 'order', 'selected', 'extra_time'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function times()
    {
        return $this->hasMany(TimeTable::class);
    }

    protected function hourlyRate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value / 100,
            set: fn($value) => $value * 100
        );
    }

    protected function hourlyRateTwo(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value / 100,
            set: fn($value) => $value * 100
        );
    }

    public function setTotalTime()
    {
        $totalTime = intval($this->extra_time) * 60;
        $this->times->each(function ($item) use (&$totalTime) {
            if ($item->started_at && $item->ended_at) {
                $startedAt = new Carbon($item->started_at);
                $endedAt = new Carbon($item->ended_at);
                $diff = $startedAt->diffInSeconds($endedAt);
                logger('diff ' . $diff);
                $totalTime += $diff;
            }
        });
        $this->update(['total_time' => $totalTime]);
    }
}
