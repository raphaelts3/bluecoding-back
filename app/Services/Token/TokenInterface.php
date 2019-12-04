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
    public function Generate(int $userId): string;

    /**
     * @param string|null $token
     * @return int
     * @throws TokenException
     */
    public function Validate(string $token = null): int;

    /**
     * @param string|null $token
     * @return void
     * @throws TokenException
     */
    public function Invalidate(string $token = null): void;
}
