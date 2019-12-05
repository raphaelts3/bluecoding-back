<?php


namespace App\Services\GifSource;


interface GifSourceInterface
{

    /**
     * @param int $maxPages
     * @return array
     */
    public function list(int $maxPages = 5): array;

    /**
     * @param array $links
     * @return array
     */
    public function download(array $links): array;
}
