<?php

namespace Tests\Feature;

use App\Enums\UserLicences;
use App\Mail\WelcomeEmail;
use App\Models\Licences;
use App\Models\Partition;
use App\Models\User;
use App\Traits\testTrait;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class userTest extends TestCase {
    use DatabaseMigrations;
    use RefreshDatabase;
    use testTrait;

    public $user = [];

    public function setUp(): void {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create([
            'role_id' => 4,
        ]);
    }

    /**
     * Registrace uživatele
     *
     * @return void
     */
    public function test_register_user() {
        $user = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->email(),
            'type' => ['value' => fake()->numberBetween(1, 2)],
            'password' => 'Aa123456#',
            'password_confirm' => 'Aa123456#',
            'confirm' => true, ];
        $response = $this->post('/register', $user);
        $this->assertDatabaseHas('users', [
            'email' => $user['email'],
        ]);
        $this->assertAuthenticated();
        Mail::fake();
        Mail::to($user['email'])->send(new WelcomeEmail(User::where('email', $user['email'])->first()));
        Mail::assertSent(WelcomeEmail::class);
        $response->assertRedirect(route('verification.notice'));
    }

    /**
     * Neúspěšná registrace uživatele - z důvodu špatného zadání hesla
     *
     * @return void
     */
    public function test_register_user_denied() {
        $user = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->email(),
            'type' => ['value' => fake()->numberBetween(1, 2)],
            'password' => 'Aa123456#',
            'password_confirm' => 'Aa12345',
            'confirm' => fake()->boolean(), ];
        $response = $this->post('/register', $user);
        $this->assertDatabaseMissing('users', [
            'email' => $user['email'],
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    /**
     * Neúspěšná registrace uživatele - z důvodu nezadání povinného atributu
     *
     * @return void
     */
    public function test_register_user_denied_attr() {
        $user = [
            'firstname' => '',
            'lastname' => '',
            'email' => fake()->email(),
            'type' => ['value' => fake()->numberBetween(1, 2)],
            'password' => 'Aa123456#',
            'password_confirm' => 'Aa12345#',
            'confirm' => fake()->boolean(), ];
        $response = $this->post('/register', $user);
        $this->assertDatabaseMissing('users', [
            'email' => $user['email'],
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    /**
     * Neúspěšná registrace uživatele - z důvodu ochrany proti botům
     *
     * @return void
     */
    public function test_register_user_denied_token() {
        $user = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->email(),
            'type' => ['value' => fake()->numberBetween(1, 2)],
            'password' => 'Aa123456#',
            'password_confirm' => 'Aa12345#',
            'confirm' => fake()->boolean(), ];
        $response = $this->post('/register', $user);
        $this->assertDatabaseMissing('users', [
            'email' => $user['email'],
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    /**
     * Přihlášení uživatele
     *
     * @return void
     */
    public function test_user_can_login() {
        $response = $this->post('/login', [
            'email' => 'navratil.jiri@atlas.cz',
            'password' => 'Aa123456#',
            'remember' => 'on',
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    /**
     * Načtení uživatelského profilu
     *
     * @return void
     */
    public function test_user_profile_can_be_rendered() {
        $this->actingAs($this->user);
        $response = $this->get(route('user.info'));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * Odhlášení uživatele
     *
     * @return void
     */
    public function test_logoff() {
        $response = $this->actingAs($this->user)->get(route('logout'));
        $response->assertStatus(302);
    }

    /**
     * Běžný uživatel si změní nastavení v profilu
     *
     * @return void
     */
    public function test_user_can_change_profile() {
        $this->actingAs($this->user);
        $userUpdate = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'type' => ['id' => fake()->numberBetween(1, 2)],
        ];
        $response = $this->put(route('user.update'), $userUpdate);
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'firstname' => $userUpdate['firstname'],
            'lastname' => $userUpdate['lastname'],
        ]);
    }

    /**
     * Změna nastavení sdílení
     *
     * @return void
     */
    public function test_user_share_change() {
        $this->actingAs($this->user);
        $userUpdate = [
            'share' => ['id' => fake()->boolean()],
        ];
        $response = $this->put(route('user.share'), $userUpdate);
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
        ]);
    }

    /**
     * Uživatel změní heslo
     *
     * @return void
     */
    public function test_user_can_change_password() {
        $this->actingAs($this->user);
        $newPassword = fake()->password(8, 20);
        $userUpdate = [
            'oldPassword' => $this->user->password,
            'newPassword' => $newPassword,
            'againNewPassword' => $newPassword,
        ];
        $response = $this->put(route('user.passwordReset'), $userUpdate);
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
        ]);
    }

    /**
     * uživatel přejde do seznamu svých předmětu (20)
     *
     * @return void
     */
    public function test_organisation_screen_can_be_rendered() {
        $this->actingAs($this->user);
        for ($x = 0; $x <= 20; $x++) {
            $this->createSubject($this->user);
        }
        $response = $this->get(route('subject.index'));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * Vytvoření předmětu
     *
     * @return void
     */
    public function test_user_can_create_subject() {
        $this->actingAs($this->user);
        $subject = [
            'name' => fake()->text(10),
            'icon' => fake()->text(5),
        ];
        $response = $this->post(route('subject.store', $subject));
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $createdSubject = User::with('patritions')->find($this->user->id);
        $this->assertDatabaseHas('partitions', [
            'id' => $createdSubject->patritions->first()->id,
        ]);
    }

    /**
     * Překročení vytvoření předmětů u standartního uživatele
     *
     * @return void
     */
    public function test_user_standart_can_create_subject_because_of_limit() {
        $this->actingAs($this->user);
        for ($x = 0; $x <= Licences::standartUserPartitions; $x++) {
            $subject = $this->createSubject($this->user);
            $subject->Users()->attach($this->user->id);
        }
        $newSubject = [
            'name' => fake()->text(10),
            'icon' => ['iconName' => fake()->text(20)],
        ];

        $response = $this->post(route('subject.store', $newSubject));
        $this->assertAuthenticated();
        $response->assertRedirect();
        $response->assertSessionHasErrors(['msg']);
    }

    public function test_user_standart_cant_create_chapter_because_of_limit() {
        $this->actingAs($this->user);
        $this->user->licences_id = UserLicences::STANDART;
        $subject = $this->createSubject($this->user);
        $subject->Users()->attach($this->user->id);
        for ($x = 0; $x <= Licences::standartUserChaptersInPartitions; $x++) {
            $chapter = $this->createChapter($subject);
            $chapter->save();
        }
        $newChapter = [
            'name' => fake()->text(10),
            'perex' => fake()->text(20),
            'contentChapter' => fake()->text(2000),
            'slug' => $subject->slug,
        ];
        $response = $this->post(route('chapter.store', ['slug' => $subject->slug]), $newChapter);
        $response->assertRedirect();
        $response->assertSessionHasErrors('msg');
    }

    /**
     * Vymazání předmětu -> současně i s navázenýma kapitolama
     *
     * @return void
     */
    public function test_user_can_delete_subject() {
        $this->actingAs($this->user);
        $subject = $this->createSubject($this->user);
        $subject->Users()->attach($this->user->id);
        for ($x = 0; $x <= fake()->numberBetween(0, 18); $x++) {
            $this->createChapter($subject);
        }
        $response = $this->delete(route('subject.destroy', $subject->id));
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseMissing('partitions', [
            'id' => $subject->id,
        ]);
    }

    /**
     * Zobrazení předmětu s kapitolami
     *
     * @return void
     */
    public function test_subject_screen_can_be_rendered() {
        $this->actingAs($this->user);
        $subject = $this->createSubject($this->user);
        $subject->Users()->attach($this->user->id);
        for ($x = 0; $x <= fake()->numberBetween(0, 18); $x++) {
            $this->createChapter($subject);
        }
        $response = $this->get(route('subject.show', $subject->slug));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * Zobrazení určité kapitoly
     *
     * @return void
     */
    public function test_chapter_can_be_rendered() {
        $this->actingAs($this->user);
        $subject = $this->createSubject($this->user);
        $subject->Users()->attach($this->user->id);
        $chapter = $this->createChapter($subject);

        $response = $this->get(route('chapter.show', ['chapter' => $chapter->slug, 'slug' => $subject->slug]));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * Vymazání kapitoly
     *
     * @return void
     */
    public function test_user_can_delete_chapter() {
        $this->actingAs($this->user);
        $subject = $this->createSubject($this->user);
        $subject->Users()->attach($this->user->id);
        $chapter = $this->createChapter($subject);

        $response = $this->delete(route('chapter.destroy', ['slug' => $subject->slug, 'chapter' => $chapter->slug]));
        $this->assertAuthenticated();
        $response->assertStatus(302);
        $this->assertDatabaseMissing('chapters', [
            'id' => $chapter->id,
        ]);
    }

    /**
     * Uživatel nemůže přistoupit k nastavnení jiného uživatele
     *
     * @return void
     */
    public function test_user_cant_access_to_another_user_profile() {
        $this->actingAs($this->user);
        $user = User::factory()->create();
        $this->get(route('adminuser.edit', $user->slug))->assertStatus(302);
        $this->assertAuthenticated();
    }

    /**
     * získání aktivních uživatelů
     *
     * @return void
     */
    public function test_user_can_access_to_list_of_active_user_for_sharing_subject() {
        $this->actingAs($this->user);
        $subject = $this->createSubject($this->user);
        $response = $this->get(route('sharing', $subject->slug));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * Uživatel si seřazuje jednotlivé předměty
     *
     * @return void
     */
    public function test_user_can_sort_subjects() {
        $this->actingAs($this->user);
        for ($i = 0; $i <= Licences::standartUserPartitions; $i++) {
            $subject = $this->createSubject($this->user);
            $subject->Users()->attach($this->user->id);
        }
        $sort = $this->getSort();
        $response = $this->get(route('subject.sort', $sort));
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }
}
