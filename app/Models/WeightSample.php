<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'flock_id',
        'coop_id',
        'user_id',
        'date',
        'sample_count',
        'average_weight_kg',
        'uniformity_percentage',
        'age_weeks',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'sample_count' => 'integer',
        'average_weight_kg' => 'decimal:3',
        'uniformity_percentage' => 'decimal:2',
        'age_weeks' => 'integer',
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
