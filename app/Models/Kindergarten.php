<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kindergarten extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'postal_code',
        'address',
        'principal',
        'establishment_date',
        'number_of_employees',
    ];
}
