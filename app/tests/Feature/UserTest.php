<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function test_users_list_if_exists_t2()
    {
        $response = $this->get('/api/users/');

        $response->assertJson(["data" => []]);
    }

    public function test_user_not_found()
    {
        $response = $this->get('/api/user/500');

        $response->assertNotFound();
    }

    public function test_users_transaction_not_enough_money_t2()
    {
        $response = $this->get('/api/user/1');
        $response->assertOk();
        $response = $this->get('/api/user/2');
        $response->assertOk();

        $response = $this->postJson('/api/user/transaction2', [
            'value' => '10000',
            'payer' => 1,
            'payee' => 2
        ]);

        $response
            ->assertStatus(400)
            ->assertJson([
                "message" => "Not enough money"
        ]);
    }

    public function test_users_transaction_seller_cant_send_money_t2()
    {
        $response = $this->get('/api/user/1');
        $response->assertOk();
        $response = $this->get('/api/user/2');
        $response->assertOk();

        $response = $this->postJson('/api/user/transaction2', [
            'value' => '10',
            'payer' => 2,
            'payee' => 1
        ]);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Sellers are not allowed to send money',
        ]);
    }

    public function test_users_transaction_success_t2()
    {
        $response = $this->get('/api/user/1');
        $response->assertOk();
        $response = $this->get('/api/user/2');
        $response->assertOk();
        
        $response = $this->postJson('/api/user/transaction2', [
            'value' => '10',
            'payer' => 1,
            'payee' => 2
        ]);

        $response->assertStatus(200);
    }

    public function test_users_list_if_exists_t1()
    {
        $response = $this->get('/api/users/');

        $response->assertJson(["data" => []]);

        // $playlists = Playlist::all();
        // $this->assertCount(1, $playlists);

        // $response = $this->get('/api/v1/playlists');

        // $response->assertStatus(200);
    }

    public function test_user_not_found_t1()
    {
        $response = $this->get('/api/user/500');

        $response->assertNotFound();

        // $playlists = Playlist::all();
        // $this->assertCount(1, $playlists);

        // $response = $this->get('/api/v1/playlists');

        // $response->assertStatus(200);
    }

    public function test_users_transaction_not_enough_money_t1()
    {
        $response = $this->get('/api/user/1');
        $response->assertOk();
        $response = $this->get('/api/user/2');
        $response->assertOk();

        $response = $this->postJson('/api/user/transaction', [
            'value' => '10000',
            'payer' => 1,
            'payee' => 2
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => 'false',
                "message" => "Not enough money"
        ]);
    }

    public function test_users_transaction_seller_cant_send_money_t1()
    {
        $response = $this->get('/api/user/1');
        $response->assertOk();
        $response = $this->get('/api/user/2');
        $response->assertOk();

        $response = $this->postJson('/api/user/transaction', [
            'value' => '10',
            'payer' => 2,
            'payee' => 1
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => 'false',
                'message' => 'Sellers are not allowed to send money',
        ]);
    }

    public function test_users_transaction_success_t1()
    {
        $response = $this->get('/api/user/1');
        $response->assertOk();
        $response = $this->get('/api/user/2');
        $response->assertOk();

        $response = $this->postJson('/api/user/transaction', [
            'value' => '10',
            'payer' => 1,
            'payee' => 2
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => 'true'
        ]);
    }
}
