<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;

class PlayerControllerDeleteTest extends PlayerControllerBaseTest
{

    protected string $token = 'SkFabTZibXE1aE14ckpQUUxHc2dnQ2RzdlFRTTM2NFE2cGI4d3RQNjZmdEFITmdBQkE';

    protected bool $userCreated = false;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function test_unauthenticated()
    {
        $res = $this->delete(self::REQ_URI . '1', headers: [
            'Accept' => 'application/json'
        ]);

        $this->assertEquals([
            'message' => 'Unauthenticated.'
        ], $res->json());
    }

    public function test_invalid_id()
    {
        $this->createUser();

        $res = $this->delete(self::REQ_URI . '1', headers: [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
            ]);

        $this->assertEquals([
            'message' => 'The selected id is invalid.'
        ], $res->json());
    }

    public function test_delete_user()
    {
        $this->createUser();
        $this->createSinglePlayer();

        $res = $this->delete(self::REQ_URI . '1', headers: [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $res->assertStatus(204);
    }

    protected function createUser(): void
    {
        if($this->userCreated) {
            return;
        }

        $this->userCreated = true;

        $user = new User();
        $user->name = 'Admin';
        $user->email = 'email@email.com';
        $user->password = Str::random(10);
        $user->save();

        $user->tokens()->create([
            'name' => 'api_token',
            'token' => hash('sha256', $this->token),
            'abilities' => ['*'],
        ]);
    }
}
