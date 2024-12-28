<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $table = 'children';

    protected $fillable = [
        'last_name',
        'first_name',
        'last_kana_name',
        'first_kana_name',
        'birthdate',
        'img',
        'admission_date',
        'medical_history', 
        'has_allergy', 
        'allergy_type',
    ];
}


