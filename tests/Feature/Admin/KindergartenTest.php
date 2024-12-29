<?php

namespace Tests\Feature\Admin;

use App\Models\Kindergarten;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KindergartenTest extends TestCase
{
    use RefreshDatabase;

    // indexアクション
    public function test_guest_user_cannot_access_kindergarten_index()
    {
        $kindergarten = Kindergarten::factory()->create();

        $response = $this->get(route('admin.kindergarten.index'));
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_cannot_access_kindergarten_index()
    {
        $user = User::factory()->create(); 
        $kindergarten = Kindergarten::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.kindergarten.index'));
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_access_kindergarten_index()
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin');
        $kindergarten = Kindergarten::factory()->create();
        $response = $this->get(route('admin.kindergarten.index'));
        $response->assertStatus(200); 
    }

    // editアクション
    public function test_guest_cannot_access_kindergarten_edit()
    {
        $kindergarten = Kindergarten::factory()->create();
        $response = $this->get(route('admin.kindergarten.edit', $kindergarten));
        $response->assertRedirect('/admin/login'); 
    }

    public function test_authenticated_user_cannot_access_kindergarten_edit()
    {
        $user = User::factory()->create();
        $kindergarten = Kindergarten::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.kindergarten.edit', $kindergarten));
        $response->assertRedirect('/admin/login'); 
    }

    public function test_authenticated_admin_can_access_kindergarten_edit()
    {
        $admin = Admin::factory()->create();
        $kindergarten = Kindergarten::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.kindergarten.edit', $kindergarten));
        $response->assertStatus(200); 
    }

    // updateアクション
    public function test_guest_in_user_cannot_update_kindergarten()
    {
        $kindergarten = Kindergarten::factory()->create();
        $response = $this->patch(route('admin.kindergarten.update', $kindergarten), [
            'name' => '新しい保育園名',
            'phone_number' => '11111111111',
            'postal_code' => '2222222',
            'address' => '新しい住所',
            'principal' => '新しい代表者',
            'establishment_date' => '2020-01-01',
            'number_of_employees' => 100,
        ]);
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_cannot_update_kindergarten()
    {
        $user = User::factory()->create();
        $kindergarten = Kindergarten::factory()->create();
        $response = $this->actingAs($user)->patch(route('admin.kindergarten.update', $kindergarten), [
            'name' => '新しい保育園名',
            'phone_number' => '11111111111',
            'postal_code' => '2222222',
            'address' => '新しい住所',
            'principal' => '新しい代表者',
            'establishment_date' => '2020-01-01',
            'number_of_employees' => 100,
        ]);
        $response->assertRedirect('/admin/login'); 
    }

    public function test_authenticated_admin_can_update_kindergarten()
    {
        $admin = Admin::factory()->create();
        $kindergarten = Kindergarten::factory()->create();
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.kindergarten.update', $kindergarten), [
            'name' => '新しい保育園名',
            'phone_number' => '11111111111',
            'postal_code' => '2222222',
            'address' => '新しい住所',
            'principal' => '新しい代表者',
            'establishment_date' => '2020-01-01',
            'number_of_employees' => 100,
        ]);
        $response->assertRedirect(route('admin.kindergarten.index')); 
        $this->assertDatabaseHas('kindergartens', ['name' => '新しい保育園名']);
    }
}

