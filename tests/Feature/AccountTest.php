<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_list_accounts()
    {
        Account::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson('/api/accounts');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_user_can_create_account()
    {
        $accountData = [
            'name' => 'Test Account',
            'type' => 'checking',
            'current_balance' => 1000.00,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/accounts', $accountData);

        $response->assertStatus(201)
            ->assertJsonFragment($accountData);

        $this->assertDatabaseHas('accounts', $accountData);
    }

    public function test_user_can_view_account()
    {
        $account = Account::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson("/api/accounts/{$account->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $account->name]);
    }

    public function test_user_can_update_account()
    {
        $account = Account::factory()->create(['user_id' => $this->user->id]);

        $updateData = ['name' => 'Updated Account Name'];

        $response = $this->actingAs($this->user)->putJson("/api/accounts/{$account->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('accounts', $updateData);
    }

    public function test_user_can_delete_account()
    {
        $account = Account::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->deleteJson("/api/accounts/{$account->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('accounts', ['id' => $account->id]);
    }
}
