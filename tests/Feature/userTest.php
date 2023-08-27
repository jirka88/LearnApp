<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class userTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Renderování registrace
     * @return void
     */
    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }
    /**
     * Renderování přihlášení
     * @return void
     */
    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
    /**
     * Registrace uživatele
     * @return void
     */
    public function test_register_user()
    {
        $user = [
            'firstname' => fake()->firstName(),
            'email' => fake()->email(),
            'type' => ['value' => fake()->numberBetween(1,2)],
            'password' => 'Aa123456#',
            'password_confirm' => 'Aa123456#',
            'confirm' => true,];
        $response = $this->post('/register', $user);
        $this->assertDatabaseHas('users', [
            'email' => $user['email'],
        ]);
        $response->assertRedirect('/dashboard');
    }

    /**
     * Přihlášení uživatele
     * @return void
     */
    public function test_login_user() {
        $response = $this->post('/login', [
            'email' => 'navratil.jiri@atlas.cz',
            'password' => 'Aa123456#',
        ]);
        $response->assertRedirect('/dashboard');
    }

    /**
     * Načtení uživatelského profilu
     * @return void
     */
    public function test_user_profile_can_be_rendered() {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get("/dashboard/user");
        $response->assertStatus(200);
    }

}
