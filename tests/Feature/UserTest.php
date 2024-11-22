<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'username' => 'maulana',
            'password' => 'rahasia',
            'name' => 'asyifa maulana'
        ])->assertStatus(201)->assertJson([
            "data" => [
                'username' => 'maulana',
                'name' => 'asyifa maulana'

            ]
        ]);
    }
    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => ''
        ])->assertStatus(400)->assertJson([
            "errors" => [
                'username' => [

                ],
                'name' => [

                ]

            ]
        ]);
    }
    public function testRegisterUsernameAlreadyExists()
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => 'maulana',
            'password' => 'rahasia',
            'name' => 'asyifa maulana'
        ])->assertStatus(400)->assertJson([
            "errors" => [
                'username' => [
                    'username already registered'
                ],
            ]
        ]);
    }


    public function testLoginSuccess(){
        $this->seed(UserSeeder::class);
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test'
        ])->assertStatus(200)->assertJson([
            'data' => [
                'username' => 'test',
                'name' => 'test'
            ]
        ]);

        $user = User::where('username', 'test')->first();
        self::assertNotNull($user->token);

    }
    public function testLoginFailed(){

    }
}
