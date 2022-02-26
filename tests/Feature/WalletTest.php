<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate($enabled = false, $balance = 0){
        User::create([
            'name' => 'test',
            'email'=>'test@gmail.com',
            'password' => bcrypt('secret1234'),
            'wallet_status' => $enabled ? 1 : 0,
            'wallet_balance' => $balance
        ]);

        $response = $this->json('POST', route('login'), [
            'email' => 'test@gmail.com',
            'password' => 'secret1234',
        ]);
        return $response->json()['data']['token']['access_token'];
    }


    public function test_enable()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', route('wallet.enable'), []);

        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->assertEquals(1, $response->json()['data']['wallet_status']);
    }

    public function test_disable()
    {
        $token = $this->authenticate(true);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', route('wallet.disable'), []);

        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->assertEquals(0, $response->json()['data']['wallet_status']);
    }

    public function test_deposit()
    {
        $token = $this->authenticate(true);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', route('wallet.create'), [
            'type' => 0,
            'amount' => 100000,
            'reference_id' => '4b01c9bb-3acd-47dc-87db-d9ac483d20b2'
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->assertEquals(100000, $response->json()['data']['wallet_balance']);
    }

    public function test_withdraw()
    {
        $token = $this->authenticate(true, 100000);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', route('wallet.create'), [
            'type' => 1,
            'amount' => 25000,
            'reference_id' => '50535246-dcb2-4929-8cc9-004ea06f5241'
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->assertEquals(75000, $response->json()['data']['wallet_balance']);
    }
}
