<?php

namespace Tests\Feature\Admin\Auth;

use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
 
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_admins_can_authenticate_using_the_login_screen(): void
    {
        $admin = new Admin();
        $admin->name = '管理花子';
        $admin->email = 'admin@exmple.com';
        $admin->password = Hash::make('1234pass');
        $admin->phone_number = '08088889999';
        $admin->role = 'staff';
        $admin->save();

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => '1234pass',
        ]);

        $this->assertTrue(Auth::guard('admin')->check());
        $response->assertRedirect(RouteServiceProvider::ADMIN_HOME);
    }

    public function test_admins_can_not_authenticate_with_invalid_password(): void
    {
        $admin = new Admin();
        $admin->name = '管理花子';
        $admin->email = 'admin@exmple.com';
        $admin->password = Hash::make('1234pass');
        $admin->phone_number = '08088889999';
        $admin->role = 'staff';
        $admin->save();

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_admins_can_logout(): void
    {
        $admin = new Admin();
        $admin->name = '管理花子';
        $admin->email = 'admin@exmple.com';
        $admin->password = Hash::make('1234pass');
        $admin->phone_number = '08088889999';
        $admin->role = 'staff';
        $admin->save();

        $response = $this->actingAs($admin, 'admin')->post('/admin/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}

