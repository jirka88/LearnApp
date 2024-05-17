<?php

namespace Tests\Feature;

use App\Models\User;
use App\Traits\testTrait;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class adminTest extends TestCase {
    use DatabaseMigrations;
    use RefreshDatabase;
    use testTrait;

    public $user = [];

    public function setUp(): void {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create([
            'role_id' => 1,
        ]);
    }

    /**
     * ADMIN - zobrazení všech uživatelů
     *
     * @return void
     */
    public function test_admin_can_render_list_of_users() {
        for ($i = 0; $i < fake()->numberBetween(1, 25); $i++) {
            $this->createUser(0);
        }
        $response = $this->actingAs($this->user)->get(route('admin'));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * ADMIN - zobrazení uživatelského profilu
     *
     * @return void
     */
    public function test_admin_can_render_user_profile() {
        $user = $this->createUser(4);
        $response = $this->actingAs($this->user)->get(route('adminuser.edit', $user->slug));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * ADMIN - zobrazení operátorův profil
     *
     * @return void
     */
    public function test_admin_can_render_operator_profile() {
        $user = $this->createUser(2);
        $response = $this->actingAs($this->user)->get(route('adminuser.edit', $user->slug));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * Vytvoření předmětu pod uživatelem
     *
     * @return void
     */
    public function test_admin_can_create_subject_to_user() {
        $user = $this->createUser(4);
        $subject = $this->createSubject($user);
        $response = $this->actingAs($this->user)->post(route('adminuser.storeSubject', $user->slug), [$subject]);
        $this->assertAuthenticated();
        $this->assertDatabaseHas('partitions', [
            'name' => $subject['name'],
            'created_by' => $user->id,
        ]);
        $response->assertStatus(302);
    }

    /**
     * ADMIN - Může vytvořit nového uživatele
     *
     * @return void
     */
    public function test_admin_can_render_page_for_creating_new_user() {
        $response = $this->actingAs($this->user)->get(route('adminuser.create'));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * Vytvoření uživatele
     *
     * @return void
     */
    public function test_admin_can_create_new_user() {
        $user = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->email(),
            'type' => ['id' => fake()->numberBetween(1, 2)],
            'role' => ['id' => fake()->numberBetween(1, 4)],
            'licence' => ['id' => fake()->numberBetween(1, 3)],
            'password' => 'Aa123456#',
            'password_confirm' => 'Aa123456#',
        ];

        $response = $this->actingAs($this->user)->post(route('adminuser.store', $user));
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'email' => $user['email'],
        ]);
    }

    /**
     * Admin může změnit politiku registrace
     */
    public function test_admin_can_change_registration_on_off() {
        $status = fake()->boolean();
        $response = $this->actingAs($this->user)->put(route('adminregister.restriction', $status));
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseHas('settings', [
            'RestrictedRegistration' => $status,
        ]);
    }
}
