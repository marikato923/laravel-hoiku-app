<?php

namespace Tests\Feature\Admin;

use App\Models\Term;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TermTest extends TestCase
{
    use RefreshDatabase;

    // indexアクション
    public function test_guest_user_cannot_access_terms_index()
    {
        $terms = Term::factory()->create();

        $response = $this->get(route('admin.terms.index'));
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_cannot_access_terms_index()
    {
        $user = User::factory()->create(); 
        $terms = Term::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.terms.index'));
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_access_terms_index()
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin');
        $terms = Term::factory()->create();
        $response = $this->get(route('admin.terms.index'));
        $response->assertStatus(200); 
    }

    // editアクション
    public function test_guest_cannot_access_terms_edit()
    {
        $terms = Term::factory()->create();
        $response = $this->get(route('admin.terms.edit', $terms));
        $response->assertRedirect('/admin/login'); 
    }

    public function test_authenticated_user_cannot_access_terms_edit()
    {
        $user = User::factory()->create();
        $terms = Term::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.terms.edit', $terms));
        $response->assertRedirect('/admin/login'); 
    }

    public function test_authenticated_admin_can_access_terms_edit()
    {
        $admin = Admin::factory()->create();
        $terms = Term::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.terms.edit', $terms));
        $response->assertStatus(200); 
    }

    // updateアクション
    public function test_guest_in_user_cannot_update_terms()
    {
        $terms = Term::factory()->create();
        $response = $this->patch(route('admin.terms.update', $terms), [
            'content' => '新しい内容',
        ]);
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_cannot_update_terms()
    {
        $user = User::factory()->create();
        $terms = Term::factory()->create();
        $response = $this->actingAs($user)->patch(route('admin.terms.update', $terms), [
            'content' => '新しい内容',
        ]);
        $response->assertRedirect('/admin/login'); 
    }

    public function test_authenticated_admin_can_update_terms()
    {
        $admin = Admin::factory()->create();
        $terms = Term::factory()->create();
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.terms.update', $terms), [
            'content' => '新しい内容',
        ]);
        $response->assertRedirect(route('admin.terms.index')); 
        $this->assertDatabaseHas('terms', ['content' => '新しい内容']);
    }
}

