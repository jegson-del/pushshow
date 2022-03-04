<?php

namespace Tests\Unit\Subscriber;

use Faker\Factory;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


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


    public function test_subscriber_can_login(){

        $subscriber = Subscriber::factory()->create();

        $response = $this->post(route('api.subscriber.login'), [
            'email' => $subscriber->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
    }

    public function test_subscriber_can_register(){

        $faker = Factory::create();

        $response = $this->post(route('api.subscriber.register'),[

            'email' => $faker->safeEmail,
            'business_name' => $faker->name,
            'password' => 'password',
            'confirm_password' => 'password'

        ]);
        $response->assertStatus(200);
    }

    public function test_subscriber_invalid_login(){

        $subscriber = Subscriber::factory()->create();

        $response = $this->post(route('api.subscriber.login'), [
            'email' => $subscriber->email,
            'password' => 'wrong_password'
        ]);

         $response->assertUnauthorized();
    }

}
