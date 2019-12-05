<?php

use App\Services\Token\TokenException;
use App\Services\Token\TokenInterface;

class AuthTest extends TestCase
{

    /**
     * @var TokenInterface
     */
    private $tokenService;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->tokenService = app(TokenInterface::class);
    }

    /**
     * Test Login
     *
     * @return void
     * @throws TokenException
     */
    public function testLogin()
    {
        $this->post(
            '/auth/login',
            [
                'email' => 'test@test.com',
                'password' => '12345678'
            ]
        );

        $response = json_decode($this->response->getContent(), true);

        $this->assertIsInt($this->tokenService->validate($response['token']));
    }
}
