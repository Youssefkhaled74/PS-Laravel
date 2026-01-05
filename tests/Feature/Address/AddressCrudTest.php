<?php

namespace Tests\Feature\Address;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Address;

class AddressCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_addresses()
    {
        $this->getJson('/api/me/addresses')->assertStatus(401);
    }

    public function test_create_address()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $payload = [
            'city' => 'Riyadh',
            'street' => 'Olaya',
            'label' => 'Home',
        ];

        $resp = $this->postJson('/api/me/addresses', $payload);

        $resp->assertStatus(201)->assertJson(['success' => true]);

        $this->assertDatabaseHas('addresses', ['user_id' => $user->id, 'city' => 'Riyadh', 'street' => 'Olaya']);
    }

    public function test_list_addresses()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Address::factory()->for($user)->count(2)->create();

        $this->actingAs($user, 'sanctum');

        $resp = $this->getJson('/api/me/addresses');

        $resp->assertStatus(200)->assertJsonCount(2, 'data');
    }

    public function test_update_address()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $address = Address::factory()->for($user)->create(['city' => 'OldCity']);

        $this->actingAs($user, 'sanctum');

        $resp = $this->patchJson('/api/me/addresses/' . $address->id, ['city' => 'NewCity']);

        $resp->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('addresses', ['id' => $address->id, 'city' => 'NewCity']);
    }

    public function test_delete_address()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $address = Address::factory()->for($user)->create();

        $this->actingAs($user, 'sanctum');

        $resp = $this->deleteJson('/api/me/addresses/' . $address->id);

        $resp->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_set_default_address()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $a1 = Address::factory()->for($user)->create(['is_default' => false]);
        $a2 = Address::factory()->for($user)->create(['is_default' => false]);

        $this->actingAs($user, 'sanctum');

        $resp = $this->patchJson('/api/me/addresses/' . $a2->id . '/default');

        $resp->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('addresses', ['id' => $a2->id, 'is_default' => 1]);
        $this->assertDatabaseHas('addresses', ['id' => $a1->id, 'is_default' => 0]);
    }
}
