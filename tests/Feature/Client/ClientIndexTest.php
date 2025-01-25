<?php
namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ClientIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_clients()
    {
        $user = User::factory()->create();

        $clients = Client::factory()->count(3)->sequence(
            ['order' => 1],
            ['order' => 2],
            ['order' => 3]
        )->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('client.index'))
            ->assertStatus(200)
            ->assertInertia(fn(AssertableInertia $page) =>
                $page
                    ->component('Dashboard/Clients')
                    ->has('clients', 3)
                    ->where('clients.0.name', $clients[0]->name)
            )
            ->assertSee('Clients'); 
    }

}
