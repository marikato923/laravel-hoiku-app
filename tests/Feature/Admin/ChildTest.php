<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Child;
use App\Models\Admin;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ChildTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_child_index_page()
    {
        $response = $this->get(route('admin.children.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_regular_user_cannot_access_child_index_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.children.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_child_index_page()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.children.index'));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_child_detail_page()
    {
        $child = Child::factory()->create();
        $response = $this->get(route('admin.children.show', $child));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_regular_user_cannot_access_child_detail_page()
    {
        $user = User::factory()->create();
        $child = Child::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.children.show', $child));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_child_detail_page()
    {
        $admin = Admin::factory()->create();
        $child = Child::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.children.show', $child));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_child_create_page()
    {
        $response = $this->get(route('admin.children.create'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_regular_user_cannot_access_child_create_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.children.create'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_child_create_page()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.children.create'));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_register_a_child()
    {
        $classroom = Classroom::factory()->create();

        $data = Child::factory()->make()->toArray();
        $data['classroom_id'] = $classroom->id;
        $response = $this->post(route('admin.children.store'), $data);
        $response->assertRedirect(route('admin.login'));
    }

    public function test_regular_user_cannot_register_a_child()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $classroom = Classroom::factory()->create();

        $data = Child::factory()->make()->toArray();
        $data['classroom_id'] = $classroom->id;

        $response = $this->post(route('admin.children.store'), $data);
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_register_a_child()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $classroom = Classroom::factory()->create();

        $data = Child::factory()->make()->toArray();
        $data['classroom_id'] = $classroom->id;
        $data['img'] = UploadedFile::fake()->image('child.jpg');

        $response = $this->post(route('admin.children.store'), $data);
        $response->assertRedirect(route('admin.children.index'));
        $this->assertDatabaseHas('children', [
            'name' => $data['name'],
            'classroom_id' => $data['classroom_id'],
        ]);
    }

    public function test_guest_cannot_access_child_edit_page()
    {
        $child = Child::factory()->create();
        $response = $this->get(route('admin.children.edit', $child));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_regular_user_cannot_access_child_edit_page()
    {
        $user = User::factory()->create();
        $child = Child::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.children.edit', $child));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_child_edit_page()
    {
        $admin = Admin::factory()->create();
        $child = Child::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.children.edit', $child));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_update_a_child()
    {
        $child = Child::factory()->create();
        $data = ['name' => 'Updated Name'];
        $response = $this->put(route('admin.children.update', $child), $data);
        $response->assertRedirect(route('admin.login'));
    }

    public function test_regular_user_cannot_update_a_child()
    {
        $user = User::factory()->create();
        $child = Child::factory()->create();
        $data = ['name' => 'Updated Name'];
        $this->actingAs($user);

        $response = $this->put(route('admin.children.update', $child), $data);
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_update_a_child()
    {
        $admin = Admin::factory()->create();
        $child = Child::factory()->create();
    
        $data = Child::factory()->make()->toArray();
        $data['name'] = 'Updated Name';
        $data['img'] = UploadedFile::fake()->image('child.jpg');
    
        $this->actingAs($admin, 'admin');

        $response = $this->put(route('admin.children.update', $child), $data);

        $response->assertRedirect(route('admin.children.show', $child));

        $this->assertDatabaseHas('children', [
            'id' => $child->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_guest_cannot_delete_a_child()
    {
        $child = Child::factory()->create();
        $response = $this->delete(route('admin.children.destroy', $child));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_regular_user_cannot_delete_a_child()
    {
        $user = User::factory()->create();
        $child = Child::factory()->create();
        $this->actingAs($user);

        $response = $this->delete(route('admin.children.destroy', $child));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_delete_a_child()
    {
        $admin = Admin::factory()->create();
        $child = Child::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->delete(route('admin.children.destroy', $child));
        $response->assertRedirect(route('admin.children.index'));
        $this->assertDatabaseMissing('children', ['id' => $child->id]);
    }
}