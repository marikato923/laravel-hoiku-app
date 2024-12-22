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
        $admin->name = '管理花子';
        $admin->email = 'admin@exmple.com';
        $admin->password = Hash::make('1234pass');
        $admin->phone_number = '08088889999';
        $admin->role = 'staff';
        $admin->save();
    }
}
