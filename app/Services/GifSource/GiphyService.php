<?php


namespace App\Services\GifSource;


use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;

class GiphyService implements GifSourceInterface
{
    /**
     * @var Client
     */
    private $apiClient;
    /**
     * @var Client
     */
    private $mediaClient;

    public function __construct()
    {
        $this->apiClient = new Client(
            ['base_uri' => 'https://api.giphy.com', 'headers' => ['api_key' => env('GIPHY_API_KEY')]]
        );
        $this->mediaClient = new Client(['base_uri' => 'https://media1.giphy.com']);
    }

    /**
     * @param int $currentPage
     * @return PromiseInterface
     */
    private function getTrending(int $currentPage): PromiseInterface
    {
        return $this->apiClient->getAsync(
            '/v1/gifs/trending',
            [
                'query' => [
                    'offset' => $currentPage * 25
                ]
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function list(int $maxPages = 5): array
    {
        $promises = [];
        $list = [
            'links' => [],
            'tags' => [],
        ];
        for ($page = 0; $page < $maxPages; $page++) {
            $promises[] = $this->getTrending($page);
        }
        $results = Promise\settle($promises)->wait();
        foreach ($results as $result) {
            /** @var Response $response */
            $response = $result['value'];
            $decodedResponse = json_decode((string)$response->getBody(), true);
            foreach ($decodedResponse['data'] as $data) {
                array_push($list['links'], $data['images']['original']['url']);
                array_push($list['tags'], explode(' ', $data['title']));
            }
        }
        return $list;
    }

    /**
     * @param string $url
     * @return PromiseInterface
     */
    private function getGif(string $url): PromiseInterface
    {
        return $this->mediaClient->getAsync(str_replace('https://media1.giphy.com/', '', $url));
    }

    /**
     * @inheritDoc
     */
    public function download(array $links): array
    {
        $downloads = [];
        $i = 0;
        foreach (array_chunk($links, 25) as $chunk) {
            $promises = [];
            foreach ($chunk as $link) {
                $promises[$i++] = $this->getGif($link);
            }
            $results = Promise\settle($promises)->wait();
            foreach ($results as $i => $result) {
                /** @var Response $response */
                $response = $result['value'];
                $downloads[$i] = $response->getBody()->getContents();
            }
        }
        return $downloads;
    }
}
