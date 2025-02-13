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
        'user_id',
        'classroom_id',
        'status',
        'rejection_reason',
    ];

    // classroomとのリレーション
    public function classroom() 
    {
        return $this->belongsTo(Classroom::class);
    }

    // attendancesとのリレーション
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'child_id');
    }

    // userとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // edit_requestsとのリレーション
    public function editRequests()
    {
        return $this->hasMany(EditRequest::class, 'child_id');
    }

    // クラス名のアクセサ
    public function getClassroomNameAttribute()
    {
        return $this->classroom->name ?? '未登録';
    }

    // ステータスのアクセサ
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'approved' => '承認済み',
            'pending' => '承認待ち',
            'rejected' => '却下',
            default => '不明',
        };
    }

    // スコープ: ステータス
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // スコープ: キーワード検索
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('last_name', 'like', "%{$keyword}%")
              ->orWhere('first_name', 'like', "%{$keyword}%")
              ->orWhere('last_kana_name', 'like', "%{$keyword}%")
              ->orWhere('first_kana_name', 'like', "%{$keyword}%");
        });
    }

    // カスケード削除
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($child) {
            $child->attendances()->delete();
            $child->editRequests()->delete();
        });
    }

    public function getImgAttribute($value)
    {
        return empty($value) ? env('DEFAULT_CHILD_IMAGE') : $value;
    }
}
