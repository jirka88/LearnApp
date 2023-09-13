<?php

namespace Tests\Feature;

use App\Models\User;
use App\Traits\testTrait;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class adminTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use testTrait;

    public $user = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create([
            "role_id" => 1
        ]);
    }

    /**
     * ADMIN - zobrazení všech uživatelů
     * @return void
     */
    public function test_admin_can_render_list_of_users()
    {
        for ($i = 0; $i < fake()->numberBetween(1, 25); $i++) {
            $this->createUser(0);
        }
        $response = $this->actingAs($this->user)->get(route('admin'));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * ADMIN - zobrazení uživatelského profilu
     * @return void
     */
    public function test_admin_can_render_user_profile()
    {
        $user = $this->createUser(4);
        $response = $this->actingAs($this->user)->get(route('adminuser.edit', $user->slug));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * ADMIN - zobrazení operátorův profil
     * @return void
     */
    public function test_admin_can_render_operator_profile()
    {
        $user = $this->createUser(2);
        $response = $this->actingAs($this->user)->get(route('adminuser.edit', $user->slug));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * ADMIN - Může vytvořit nového uživatele
     * @return void
     */
    public function test_admin_can_render_page_for_creating_new_user()
    {
        $response = $this->actingAs($this->user)->get(route('adminuser.create'));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }
    public function test_admin_can_create_new_user()
    {
        $user = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->email(),
            'type' => ['id' => fake()->numberBetween(1, 2)],
            'role' => ['id' => fake()->numberBetween(1,4)],
            'licence' => ['id' => fake()->numberBetween(1,3)],
            'password' => 'Aa123456#',
            'password_confirm' => 'Aa123456#',
        ];

        $response = $this->actingAs($this->user)->post(route('adminuser.store', $user));
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'email' => $user['email']
        ]);
    }
}
