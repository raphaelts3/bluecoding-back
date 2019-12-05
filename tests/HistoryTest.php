<?php

use App\Services\Token\TokenInterface;
use Laravel\Lumen\Testing\DatabaseTransactions;

class HistoryTest extends TestCase
{
    use DatabaseTransactions;

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
     * Test History
     *
     * @return void
     */
    public function testHistory()
    {
        $token = $this->tokenService->generate(1);
        $this->json(
            'GET',
            '/user/history',
            [],
            [
                'Authorization' => 'Bearer ' . $token
            ]
        )->seeJsonContains(['pagination' => ['limit' => 25, 'offset' => 0]]);
    }
}
