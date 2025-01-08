<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age_group',
    ];

    // childrenとのリレーション
    public function children() 
    {
        return $this->hasMany(Child::class);
    }

    //　Childモデルを通じて出席情報を取得
    public function attendances()
    {
        return $this->hasManyThrough(Attendance::class, Child::class);
    }

    // Childモデルを通じて欠席情報を取得
    public function absences()
    {
        return $this->hasManyThrough(Attendance::class, Child::class)
            ->whereNull('arrival_time');
    }
}
