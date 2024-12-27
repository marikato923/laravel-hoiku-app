<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $table = 'children';

    protected $fillable = [
        'name',
        'kana',
        'birthdate',
        'img',
        'admission_date',
        'medical_history', 
        'has_allergy', 
        'allergy_type',
    ];
}


