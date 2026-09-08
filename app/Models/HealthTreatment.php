<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthTreatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'flock_id',
        'coop_id',
        'user_id',
        'date',
        'time',
        'type',
        'medicine_name',
        'dosage',
        'application_method',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function flock()
    {
        return $this->belongsTo(Flock::class);
    }

    public function coop()
    {
        return $this->belongsTo(Coop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
