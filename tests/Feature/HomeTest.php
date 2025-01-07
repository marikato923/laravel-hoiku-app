<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_access_user_home()
    {
       $response = $this->get(route('home'));

       $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_user_home()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_authenticated_admin_cannot_access_user_home()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('home'));
        $response->assertRedirect(route('admin.home'));
    }
}
