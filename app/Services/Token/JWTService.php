<?php


namespace App\Services\Token;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Cache;

class JWTService implements TokenInterface
{

    /**
     * @inheritDoc
     */
    public function Validate(string $token = null): int
    {
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            if (!Cache::get(self::CACHE_PREFIX . $token)) {
                throw new TokenException("Not found");
            }
            return (int)$credentials->sub;
        } catch (ExpiredException $e) {
            throw new TokenException($e);
        } catch (\Exception $e) {
            throw new TokenException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function Generate(int $userId): string
    {
        $payload = [
            'iss' => env('APP_NAME') . '-' . env('APP_ENV'),
            'sub' => $userId,
            'iat' => time(),
            'exp' => time() + self::LIFE_TIME
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'));
        Cache::put(self::CACHE_PREFIX . $token, true, self::LIFE_TIME);
        return $token;
    }

    /**
     * @inheritDoc
     */
    public function Invalidate(string $token = null): void
    {
        try {
            JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            Cache::forget(self::CACHE_PREFIX . $token);
        } catch (ExpiredException $e) {
            throw new TokenException($e);
        } catch (\Exception $e) {
            throw new TokenException($e);
        }
    }
}
