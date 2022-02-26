<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{

    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    protected function authenticate()
    {
        User::create([
            'name' => 'test',
            'email'=>'test@gmail.com',
            'password' => bcrypt('secret1234')
        ]);

        $response = $this->json('POST', route('login'), [
            'email' => 'test@gmail.com',
            'password' => 'secret1234',
        ]);

        return $response;
    }

    protected function deleteAuthData()
    {
        User::where('email','test@gmail.com')->delete();
    }

    public function test_register()
    {
        $data = [
            'email' => 'test@gmail.com',
            'name' => 'Test',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ];

        $response = $this->json('POST', route('register'), $data);
        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('token', $response->json()['data']);

        User::where('email', 'test@gmail.com')->delete();
    }

    public function test_login()
    {
        $response = $this->authenticate();
        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('token', $response->json()['data']);
        $this->deleteAuthData();
    }


    public function test_me()
    {
        $response = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $response->json()['data']['token']['access_token'],
        ])->json('POST', route('me'), [
            'email' => 'test@gmail.com',
            'password' => 'secret1234',
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->deleteAuthData();
    }

    public function test_logout()
    {
        $response = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $response->json()['data']['token']['access_token'],
        ])->json('POST', route('logout'), []);

        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->deleteAuthData();
    }

    public function test_refresh()
    {
        $response = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $response->json()['data']['token']['access_token'],
        ])->json('POST', route('refresh'), []);

        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->deleteAuthData();
    }
}
