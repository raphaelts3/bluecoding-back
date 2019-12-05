<?php


namespace App\Services\Minify;


interface MinifyInterface
{
    /**
     * @param int $value
     * @return string
     */
    public function encode(int $value): string;

    /**
     * @param string $hash
     * @return int
     */
    public function decode(string $hash): int;
}
