<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new Admin();
        $admin->last_name = '保育';
        $admin->first_name = '花子';
        $admin->email = 'admin2@hoiku.com';
        $admin->password = Hash::make('1234pass');
        $admin->role = '保育士';
        $admin->save();        
    }
}
