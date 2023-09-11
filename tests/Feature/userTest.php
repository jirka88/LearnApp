<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Partition;
use App\Models\Permission;
use App\Models\User;
use App\Traits\testTrait;
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
    use testTrait;

    public $user = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create([
            "role_id" => 4
        ]);
    }

    /**
     * Registrace uživatele
     * @return void
     */
    public function test_register_user()
    {
        $user = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
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
    public function test_user_can_login()
    {
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
    public function test_user_can_change_profile()
    {
        $userUpdate = [
            "firstname" => fake()->firstName(),
            "lastname" => fake()->lastName(),
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
            "lastname" => $userUpdate["lastname"]
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
    public function test_user_can_change_password() {
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
    public function test_user_can_create_subject() {
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
     * Vymazání předmětu -> současně i s navázenýma kapitolama
     * @return void
     */
    public function test_user_can_delete_subject() {
    $subject = $this->createSubject($this->user);
    $subject->Users()->attach($this->user->id);
    for ($x = 0; $x <= fake()->numberBetween(0,18); $x++) {
        Chapter::factory()->create([
            "partition_id" => $subject->id,
        ]);
    }
    $response = $this->actingAs($this->user)->delete(route("subject.destroy", $subject->id));
    $this->assertAuthenticated();
    $response->assertStatus(200);
    $this->assertDatabaseMissing("partitions", [
        "id" => $subject->id,
    ]);
}
    /**
     * Zobrazení předmětu s kapitolami
     * @return void
     */
   public function test_subject_screen_can_be_rendered() {
       $subject = $this->createSubject($this->user);
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

    /**
     * Zobrazení určité kapitoly
     * @return void
     */
   public function test_chapter_can_be_rendered() {
       $subject = $this->createSubject($this->user);
       $subject->Users()->attach($this->user->id);
       $chapter = $this->createChapter($subject);

       $response = $this->actingAs($this->user)->get(route('chapter.show', ['chapter' => $chapter->slug, 'slug' => $subject->slug]));
       $this->assertAuthenticated();
       $response->assertStatus(200);
   }

    /**
     * Vymazání kapitoly
     * @return void
     */
   public function test_user_can_delete_chapter() {
       $subject = $this->createSubject($this->user);
       $subject->Users()->attach($this->user->id);
       $chapter = $this->createChapter($subject);

       $response = $this->actingAs($this->user)->delete(route('chapter.destroy', ["slug" => $subject->slug, "chapter" => $chapter->slug]));
       $this->assertAuthenticated();
       $response->assertStatus(302);
       $this->assertDatabaseMissing("chapters", [
           "id" => $chapter->id,
       ]);
   }

    /**
     * Uživatel nemůže přistoupit k nastavnení jiného uživatele
     * @return void
     */
   public function test_user_cant_access_to_another_user_profile() {
       $user = User::factory()->create();
       $this->actingAs($this->user)->get(route('adminuser.edit', $user->slug))->assertRedirect("/dashboard")->assertStatus(302);
       $this->assertAuthenticated();
   }
   public function test_user_can_access_to_list_of_active_user_for_sharing_subject() {
       /*$subject = $this->createSubject($this->user);
       dd($subject);*/
       //$this->actingAs($this->user)->get(route('sharing'));
   }

}
