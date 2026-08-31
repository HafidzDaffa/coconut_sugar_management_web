<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_code',
        'name',
        'legal_entity_number',
        'established_date',
        'address',
        'city',
        'province',
        'postal_code',
        'latitude',
        'longitude',
        'person_in_charge',
        'phone',
        'email',
        'status',
        'daily_capacity_kg',
        'notes',
    ];

    protected $casts = [
        'established_date' => 'date:Y-m-d',
        'latitude' => 'float',
        'longitude' => 'float',
        'daily_capacity_kg' => 'float',
    ];
}
