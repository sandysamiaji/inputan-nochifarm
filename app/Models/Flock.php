<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flock extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'start_date',
        'initial_population',
        'current_population',
        'breed',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function coops()
    {
        return $this->hasMany(Coop::class);
    }

    public function eggProductions()
    {
        return $this->hasMany(EggProduction::class);
    }

    public function feedConsumptions()
    {
        return $this->hasMany(FeedConsumption::class);
    }

    public function mortalities()
    {
        return $this->hasMany(Mortality::class);
    }
}
