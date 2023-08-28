<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
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
            'type' => ['value' => fake()->numberBetween(1, 2)],
            'password' => 'Aa123456#',
            'password_confirm' => 'Aa123456#',
            'confirm' => true,];
        $response = $this->post('/register', $user);
        $this->assertDatabaseHas('users', [
            'email' => $user['email'],
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    /**
     * Přihlášení uživatele
     * @return void
     */
    public function test_login_user()
    {
        /*$user = User::factory()->create([
            'password' => bcrypt('Aa123456#'),
        ]);*/
        $response = $this->post('/login', [
            'email' => "navratil.jiri@atlas.cz",
            'password' => 'Aa123456#',
            'remember' => 'on',
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    /**
     * Načtení uživatelského profilu
     * @return void
     */
    public function test_user_profile_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get("/dashboard/user");
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * Běžný uživatel si změní nastavení v profilu
     * @return void
     */
    public function test_user_profile_change()
    {
        $user = User::factory()->create();
        $userUpdate = [
            "firstname" => fake()->firstName(),
            "type" => ["id" => fake()->numberBetween(1, 2)],
            "active" => 1,
            "role" => ["id" => fake()->numberBetween(1, 2)]
        ];
        $response = $this->actingAs($user)->put('/dashboard/user', $userUpdate);
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            "id" => $user->id,
            "firstname" => $userUpdate["firstname"],
        ]);
    }

    /**
     * Změnění nastavení sdílení
     * @return void
     */
    public function test_user_share_change() {
        $user = User::factory()->create();
        $userUpdate = [
            "share" => ["id" => fake()->boolean()],
        ];
        $response = $this->actingAs($user)->put(route('user.share'), $userUpdate);
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            "id" => $user->id,
        ]);
    }

    /**
     * Uživatel změní heslo
     * @return void
     */
    public function test_user_change_password() {
        $user = User::factory()->create();
        $newPassword = fake()->password(8,20);
        $userUpdate = [
            "oldPassword" => $user->password,
            "newPassword" => $newPassword,
            "againNewPassword" => $newPassword,
        ];
        $response = $this->actingAs($user)->put(route('user.passwordReset'), $userUpdate);
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            "id" => $user->id,
        ]);
    }
}
