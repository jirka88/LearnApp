<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class guestTest extends TestCase {
    use DatabaseMigrations;
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();
        $this->seed();
    }

    /**
     * Renderování hlavní stránky
     *
     * @return void
     */
    public function test_main_page_can_be_rendered() {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Renderování registrace
     *
     * @return void
     */
    public function test_registration_screen_can_be_rendered() {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /**
     * Renderování přihlášení
     *
     * @return void
     */
    public function test_login_screen_can_be_rendered() {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Guest se nemůže dostat k dashboardu
     *
     * @return void
     */
    public function test_guest_cant_access_to_dashboard() {
        $this->get('/dashboard')->assertRedirect('/login')->assertStatus(302);
    }
}
