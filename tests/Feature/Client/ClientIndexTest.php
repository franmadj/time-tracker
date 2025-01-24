<?php

namespace Tests\Feature\Client;

use Tests\TestCase;
use App\Models\Client;
use Inertia\Testing\AssertableInertia as Assert;

class ClientIndexTest extends TestCase
{
    public function test_index_displays_clients()
    {
        $clients = Client::factory()->count(3)->create();

        $response = $this->get(route('client.index'));

        $response->dump();

        $response->assertInertia(fn (Assert $page) =>
            $page->component('Dashboard/Clients')
                ->has('clients', 3)
        );
    }
}
