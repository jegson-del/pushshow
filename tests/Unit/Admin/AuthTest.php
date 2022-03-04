<?php

namespace Tests\Unit\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_admin_can_login()
    {
        $admin = Admin::factory()->create();

        $response = $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'password'
        ]);
        $response->assertStatus(200);
    }

    public function test_admin_invalid_login()
    {
        $admin = Admin::factory()->create();

        $response = $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'wrongkey'
        ]);

        $response->assertUnauthorized();
    }

    public function test_admin_can_register()
    {
       $faker = \Faker\Factory::create();

       $response = $this->post(route('admin.register'), [
           'email' => $faker->safeEmail,
           'username' => $faker->name,
           'password' => 'password',
           'confirm_password' => 'password'
       ]);

       $response->assertStatus(200);
    }
}
