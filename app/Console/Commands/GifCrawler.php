<?php


namespace App\Console\Commands;


use App\Gif;
use App\Services\GifSource\GifSourceInterface;
use App\Services\Minify\Base62Service;
use App\Tag;
use Illuminate\Console\Command;

class GifCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gif:crawl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send drip e-mails to a user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param GifSourceInterface $gifService
     * @return mixed
     */
    public function handle(GifSourceInterface $gifService, Base62Service $base62Service)
    {
        $data = $gifService->list(1);
        foreach ($gifService->download($data['links']) as $i => $content) {
            $this->createGif($content, $data['tags'][$i], $base62Service);
        }
    }

    /**
     * @param array $tags
     * @param string $content
     */
    private function createGif(string $content, array $tags, $base62Service)
    {
        $path = 'gifs/' . uniqid('', true) . '.gif';
        file_put_contents(public_path($path), $content);
        $gif = new Gif();
        $gif->path = $path;
        $gif->save();
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['tag' => $tagName]);
            $tag->gifs()->save($gif);
        }
    }
}
