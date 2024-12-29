<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ClassroomTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin_classroom_index_page()
    {
        $response = $this->get('/admin/classrooms');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_users_can_access_admin_classroom_index_page()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get('/admin/classrooms');
        $response->assertStatus(200);
        $response->assertViewIs('admin.classrooms.index');
    }

    public function test_guests_cannot_store_classroom()

    {
        $response = $this->post('/admin/classrooms', ['name' => 'テスト']);
        $response->assertRedirect('/admin/login');
    }

    public function test_regular_users_cannot_store_classroom()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/admin/classrooms', ['name' => 'テスト']);
        $response->assertStatus(302);
    }

    public function test_admin_can_store_classroom()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->post('/admin/classrooms', ['name' => 'テスト']);
        $response->assertRedirect('/admin/classrooms');
        $this->assertDatabaseHas('classrooms', ['name' => 'テスト']);
    }

    public function test_guests_cannot_update_classroom()
    {
        $classroom = Classroom::factory()->create(['name' => 'はな組']);

        $response = $this->put("/admin/classrooms/{$classroom->id}", ['name' => 'そら組']);
        $response->assertRedirect('/admin/login');
    }

    public function test_regular_users_cannot_update_classroom()
    {
        $classroom = Classroom::factory()->create(['name' => 'はな組']);

        $response = $this->put("/admin/classrooms/{$classroom->id}", ['name' => 'そら組']);
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_users_can_update_classroom()
    {
        $classroom = Classroom::factory()->create(['name' => 'はな組']);
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin'); 

        $response = $this->put("/admin/classrooms/{$classroom->id}", ['name' => 'そら組']);
        $response->assertRedirect('/admin/classrooms');
        $this->assertDatabaseHas('classrooms', ['name' => 'そら組']);
    }

    public function test_guests_cannot_destroy_classroom()
    {
        $classroom = Classroom::factory()->create();

        $response = $this->delete("/admin/classrooms/{$classroom->id}");
        $response->assertRedirect('/admin/login');
    }

    public function test_regular_users_cannot_destroy_classroom()
    {
        $classroom = Classroom::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete("/admin/classrooms/{$classroom->id}");
        $response->assertStatus(302);
    }

    public function test_admin_users_can_destroy_classroom()
    {
        $classroom = Classroom::factory()->create();
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin'); 

        $response = $this->delete("/admin/classrooms/{$classroom->id}");
        $response->assertRedirect('/admin/classrooms');
        $this->assertDatabaseMissing('classrooms', ['id' => $classroom->id]);
    }
}
