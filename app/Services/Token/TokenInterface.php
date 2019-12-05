<?php


namespace App\Services\Token;


interface TokenInterface
{
    public const LIFE_TIME = 24 * 60 * 60;
    public const CACHE_PREFIX = 'token:';

    /**
     * @param int $userId
     * @return string
     */
    public function generate(int $userId): string;

    /**
     * @param string|null $token
     * @return int
     * @throws TokenException
     */
    public function validate(string $token = null): int;

    /**
     * @param string|null $token
     * @return void
     * @throws TokenException
     */
    public function invalidate(string $token = null): void;
}
