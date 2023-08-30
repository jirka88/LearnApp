<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Partition;
use App\Models\Permission;
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

    public $user = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create();
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
     * Neúspěšná registrace uživatele
     * @return void
     */
    public function test_register_user_denied()
    {
        $user = [
            'firstname' => fake()->firstName(),
            'email' => 'navratil.jiri@atlas.cz',
            'type' => ['value' => fake()->numberBetween(1, 2)],
            'password' => 'Aa123',
            'password_confirm' => 'Aa12',
            'confirm' => true,];
        $response = $this->post('/register', $user);
        $this->assertDatabaseHas('users', [
            'email' => $user['email'],
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
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
        $response = $this->actingAs($this->user)->get("/dashboard/user");
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * Odhlášení uživatele
     * @return void
     */
    public function test_logoff() {
        $response = $this->actingAs($this->user)->get(route("logout"));
        $response->assertStatus(302);
    }
    /**
     * Běžný uživatel si změní nastavení v profilu
     * @return void
     */
    public function test_user_profile_change()
    {
        $userUpdate = [
            "firstname" => fake()->firstName(),
            "type" => ["id" => fake()->numberBetween(1, 2)],
            "active" => 1,
            "role" => ["id" => fake()->numberBetween(1, 2)]
        ];
        $response = $this->actingAs($this->user)->put(route('user.update'), $userUpdate);
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            "id" => $this->user->id,
            "firstname" => $userUpdate["firstname"],
        ]);
    }

    /**
     * Změnění nastavení sdílení
     * @return void
     */
    public function test_user_share_change() {
        $userUpdate = [
            "share" => ["id" => fake()->boolean()],
        ];
        $response = $this->actingAs($this->user)->put(route('user.share'), $userUpdate);
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            "id" => $this->user->id,
        ]);
    }

    /**
     * Uživatel změní heslo
     * @return void
     */
    public function test_user_change_password() {
        $newPassword = fake()->password(8,20);
        $userUpdate = [
            "oldPassword" => $this->user->password,
            "newPassword" => $newPassword,
            "againNewPassword" => $newPassword,
        ];
        $response = $this->actingAs($this->user)->put(route('user.passwordReset'), $userUpdate);
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            "id" => $this->user->id,
        ]);
    }

    /**
     * uživatel přejde do seznamu svých předmětu (20)
     * @return void
     */
    public function test_organisation_screen_can_be_rendered() {
        for ($x = 0; $x <= 20; $x++) {
            Partition::factory()->create([
                "created_by" => $this->user,
            ]);
        }
        $response = $this->actingAs($this->user)->get(route('subject.index'));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * Vytvoření předmětu
     * @return void
     */
    public function test_create_subject() {
        $subject = [
          'name' => fake()->text(10),
          'icon' => ['iconName' => fake()->text(20)]
        ];
        $response = $this->actingAs($this->user)->post(route('subject.store', $subject));
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $createdSubject = User::with("patritions")->find($this->user->id);
        $this->assertDatabaseHas('partitions', [
            "id" => $createdSubject->patritions->first()->id,
        ]);
    }
    /**
     * Zobrazení předmětu s kapitolami
     * @return void
     */
   public function test_subject_screen_can_be_rendered() {
       $subject = Partition::factory()->create([
           "created_by" => $this->user,
       ]);
       $subject->Users()->attach($this->user->id);
       for ($x = 0; $x <= fake()->numberBetween(0,18); $x++) {
           Chapter::factory()->create([
               "partition_id" => $subject->id,
           ]);
       }
       $response = $this->actingAs($this->user)->get(route("subject.show", $subject->slug));
       $this->assertAuthenticated();
       $response->assertStatus(200);
   }
}
