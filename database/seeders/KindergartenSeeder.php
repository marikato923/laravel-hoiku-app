<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kindergarten;

class KindergartenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kindergarten = new Kindergarten();
        $kindergarten->name = 'サンプル保育園';
        $kindergarten->phone_number = '00000000000';
        $kindergarten->postal_code = '0000000';
        $kindergarten->address = '東京都サンプル区サンプル１−１';
        $kindergarten->principal = '保育太郎';
        $kindergarten->establishment_date = '1977-05-05';
        $kindergarten->number_of_employees = '25名';
        
        $kindergarten->save();
    }
}
