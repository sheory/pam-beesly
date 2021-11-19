<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    #DatabaseMigrations - Guarantees that the testing db is always clean before and after each test
    use DatabaseMigrations;
    /**
     * @test
     */
    public function can_create_an_event()
    {
        //what I need previously
        $data = Event::factory()->make();

        //what I want to do
        $response = $this->json('POST', 'api/events', $data->toArray());


        //what I am expecting
        $body = $response->getData();

        $response->assertStatus(201);
    }
}
