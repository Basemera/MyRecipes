<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;

class UserControllerTest extends \TestCase {
    public function testcreateUserSuccess()
    {
        $data = [
            'username' => 'Rita',
            'email' => 'rita@g.com',
            'password' => 'Rita'
        ];
        $this->post('http://localhost:8000/myrecipes/user', $data);
       $response =  json_decode($this->response->getContent());
        $this->assertEquals('Rita', $response->username);
        $this->assertResponseStatus(201);
    }

    public function testCreateUserFailure() {
        $data = [
            'email' => 'rita@g.com',
            'password' => 'Rita'
        ];
        $this->post('http://localhost:8000/myrecipes/user', $data);
        $response =  json_decode($this->response->getContent());
        $this->assertEquals(['The username field is required.'], $response->username);
        $this->assertResponseStatus(422);
    }

    public function testcreateUserFailureWithNoEmail()
    {
        $data = [
            'username' => 'Rita',

            'password' => 'Rita'
        ];
        $this->post('http://localhost:8000/myrecipes/user', $data);
        $response =  json_decode($this->response->getContent());
        $this->assertEquals(['The email field is required.'], $response->email);
        $this->assertResponseStatus(422);
    }

    public function testcreateUserFailureWithNoPassword()
    {
        $data = [
            'username' => 'Rita',

            'email' => 'Rita@g.com'
        ];
        $this->post('http://localhost:8000/myrecipes/user', $data);
        $response =  json_decode($this->response->getContent());
        $this->assertEquals(['The password field is required.'], $response->password);
        $this->assertResponseStatus(422);
    }

    public function testcreateUserFailureWhenEmailNotUnique()
    {
        $data = [
            'username' => 'Rita',
            'email' => 'rita@g.com',
            'password' => 'Rita',
        ];

        $newUser = new User();
        $newUser->create($data);

        $data = [
            'username' => 'Jane',
            'email' => 'rita@g.com',
            'password' => 'Rita'
        ];
        $this->post('http://localhost:8000/myrecipes/user', $data);
        $response =  json_decode($this->response->getContent());
        $this->assertEquals(['The email has already been taken.'], $response->email);
        $this->assertResponseStatus(422);
    }

    public function testcreateUserFailureWhenUsernamelNotUnique()
    {
        $data = [
            'username' => 'Rita',
            'email' => 'rita@g.com',
            'password' => 'Rita',
        ];

        $newUser = new User();
        $newUser->create($data);

        $data = [
            'username' => 'Rita',
            'email' => 'jane@g.com',
            'password' => 'Rita'
        ];
        $this->post('http://localhost:8000/myrecipes/user', $data);
        $response =  json_decode($this->response->getContent());
        $this->assertEquals(['The username has already been taken.'], $response->username);
        $this->assertResponseStatus(422);
    }

    public function testLogInUserSuccess() {
        $data = [
            'username' => 'Rita',
            'email' => 'rita@g.com',
            'password' => 'Rita',
        ];
        $this->post('http://localhost:8000/myrecipes/user', $data);

        $this->post('http://localhost:8000/myrecipes/login', $data);
        $response = json_decode($this->response->getContent());
        $this->assertResponseStatus(200);
    }


    public function testLogInUserFailureWhenEmailAbsent() {
        $data = [
            'username' => 'Rita',
            'email' => 'rita@g.com',
            'password' => 'Rita',
        ];

        $newUser = new User();
        $newUser->create($data);

        $data = [
            'username' => 'Rita',
            'email' => 'p@h.com',
            'password' => 'Rita'
        ];

        $this->post('http://localhost:8000/myrecipes/login', $data);
        $response = json_decode($this->response->getContent());
        $this->assertResponseStatus(400);
        $this->assertEquals('Email does not exist.', $response->error);
    }

    public function testLogInUserFailureWhenPasswordIsWrong() {
        $data = [
            'username' => 'Rita',
            'email' => 'rita@g.com',
            'password' => 'Rita',
        ];

        $newUser = new User();
        $newUser->create($data);

        $data = [
            'username' => 'Jane',
            'email' => 'rita@g.com',
            'password' => 'Jane',
        ];

        $this->post('http://localhost:8000/myrecipes/login', $data);
        $response = json_decode($this->response->getContent());
        $this->assertResponseStatus(400);
        $this->assertEquals('Email or password is wrong.', $response->error);
    }

    public function testOnlyAdminCanGetAllUsersSuccess() {
        $data = [
            'username' => 'phiona',
            'email' => 'phiona@g.com',
            'password' => 'phiona',
        ];
        $this->post('http://localhost:8000/myrecipes/user', $data);

        $this->post('http://localhost:8000/myrecipes/login', $data);
        $response = json_decode($this->response->getContent());
        $token = $response->token;
        $this->get('http://localhost:8000/myrecipes/user',['Authorization' => $token]);
        $response = json_decode($this->response->getContent());
        $this->assertResponseStatus(200);

    }
    public function testOnlyAdminCanGetAllUsersFailure() {
        $data = [
            'username' => 'stella',
            'email' => 'stella@g.com',
            'password' => 'stella',
        ];
        $this->post('http://localhost:8000/myrecipes/user', $data);
        $this->post('http://localhost:8000/myrecipes/login', $data);
        $response = json_decode($this->response->getContent());
        $token = $response->token;
        $this->get('http://localhost:8000/myrecipes/user',['Authorization' => $token]);
        $response = json_decode($this->response->getContent());
        $this->assertResponseStatus(400);
        $this->assertEquals('Unauthorized.', $response->error);

    }

    public function testOnlyAdminCanEditSuccess() {
        $data = [
            'username' => 'phiona',
            'email' => 'phiona@g.com',
            'password' => 'phiona',
        ];

        $user = [
            'username' => 'Jane',
            'email' => 'rita@g.com',
            'password' => 'Jane',
        ];

        $this->post('http://localhost:8000/myrecipes/login', $data);
        $response = json_decode($this->response->getContent());
        $token = $response->token;
        $this->put('http://localhost:8000/myrecipes/user/2',$user, ['Authorization' => $token]);
        $response = json_decode($this->response->getContent());
        $this->assertResponseStatus(200);
        $this->assertEquals('Jane', $response->username);

    }

    public function testOnlyAdminCanGetSingleUserSuccess() {
        $data = [
            'username' => 'phiona',
            'email' => 'phiona@g.com',
            'password' => 'phiona',
        ];
        $this->post('http://localhost:8000/myrecipes/login', $data);
        $response = json_decode($this->response->getContent());
        $token = $response->token;
        $this->get('http://localhost:8000/myrecipes/user/3',['Authorization' => $token]);
        $response = json_decode($this->response->getContent());
        $this->assertResponseStatus(200);

    }

    public function testOnlyAdminCanGetSingleUserFailure() {$data = [
        'username' => 'stella',
        'email' => 'stella@g.com',
        'password' => 'stella',
    ];
        $this->post('http://localhost:8000/myrecipes/user', $data);
        $this->post('http://localhost:8000/myrecipes/login', $data);
        $response = json_decode($this->response->getContent());
        $token = $response->token;
        $this->get('http://localhost:8000/myrecipes/user/3',['Authorization' => $token]);
        $response = json_decode($this->response->getContent());
        $this->assertResponseStatus(400);
        $this->assertEquals('Unauthorized.', $response->error);
    }


    public function testOnlyAdminCanDeleteUserSuccess() {
        $data = [
            'username' => 'phiona',
            'email' => 'phiona@g.com',
            'password' => 'phiona',
        ];

        $this->post('http://localhost:8000/myrecipes/login', $data);
        $response = json_decode($this->response->getContent());
        $token = $response->token;
        $this->delete('http://localhost:8000/myrecipes/user/2', [], ['Authorization' => $token]);
        $response = json_decode($this->response->getContent());
        dd($response);
        $this->assertResponseStatus(200);
//        $this->assertEquals('successfully deleted', $response);

    }

    public function testOnlyAdminCanDeleteUserFailure() {
        $data = [
        'username' => 'stella',
        'email' => 'stella@g.com',
        'password' => 'stella',
    ];
        $this->post('http://localhost:8000/myrecipes/user', $data);
        $this->post('http://localhost:8000/myrecipes/login', $data);
        $response = json_decode($this->response->getContent());
        $token = $response->token;
        $this->delete('http://localhost:8000/myrecipes/user/2', [], ['Authorization' => $token]);
        $response = json_decode($this->response->getContent());
        $this->assertResponseStatus(400);
        $this->assertEquals('Unauthorized.', $response->error);

    }
}



