<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Child;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存のデータを削除
        DB::table('attendances')->truncate();

        $children = Child::all();

        // 今月と先月の平日リストを作成
        $today = Carbon::now();
        $dates = collect();

        for ($i = 0; $i < 2; $i++) {
            $month = $today->copy()->subMonth($i);
            for ($day = 1; $day <= $month->daysInMonth; $day++) {
                $date = $month->copy()->day($day);
                if ($date->isWeekday()) { // 平日チェック
                    $dates->push($date->toDateString());
                }
            }
        }

        foreach ($children as $child) {
            foreach ($dates as $date) {
                // 登園時間
                $arrival_time = Carbon::parse("{$date} " . rand(7, 9) . ":" . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT));

                // 退園時間
                $departure_time = Carbon::parse("{$date} " . rand(16, 19) . ":" . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT));

                // 登園レコード（退園時間はnull）
                Attendance::create([
                    'child_id' => $child->id,
                    'arrival_time' => $arrival_time,
                    'departure_time' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 退園レコード（登園時間はnull）
                Attendance::create([
                    'child_id' => $child->id,
                    'arrival_time' => null,
                    'departure_time' => $departure_time,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}