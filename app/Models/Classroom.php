<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    // 園児とのリレーション
    public function children() 
    {
        return $this->hasMany(Child::class);
    }
}
