<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mortality extends Model
{
    use HasFactory;

    protected $fillable = [
        'flock_id',
        'coop_id',
        'user_id',
        'date',
        'time',
        'count',
        'type',
        'cause',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'count' => 'integer',
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
