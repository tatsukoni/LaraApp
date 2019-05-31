<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');
        $response->assertStatus(302);

        $user = factory(User::class)->create();
        $responce = $this->actingAs($user)->get('/');
        $responce->assertStatus(200);

        $responce = $this->get('/no_path');
        $responce->assertStatus(404);
    }
}
