<?php


namespace App\Services\Minify;


class Base62Service implements MinifyInterface
{

    public const BASE62 = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    /**
     * @inheritDoc
     */
    public function encode(int $value): string
    {
        $out = '';
        $tmp = $value;
        $len = strlen(self::BASE62);
        while ($tmp > $len) {
            $out .= self::BASE62[$tmp % $len];
            $tmp = intdiv($tmp, $len);
        }
        $out .= self::BASE62[$tmp % $len];
        return $out;
    }

    /**
     * @inheritDoc
     */
    public function decode(string $hash): int
    {
        $len = strlen(self::BASE62);
        $weight = 1;
        $out = 0;
        foreach (str_split($hash) as $pos => $ch) {
            $out += strpos(self::BASE62, $ch) * $weight;
            $weight = ($pos + 1) * $len;
        }
        return $out;
    }
}
