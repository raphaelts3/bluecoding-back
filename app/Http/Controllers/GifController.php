<?php

namespace App\Http\Controllers;

use App\Events\SearchEvent;
use App\Gif;
use App\Services\Minify\MinifyInterface;
use App\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class GifController extends BaseController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var int
     */
    private $limit;
    /**
     * @var int
     */
    private $offset;
    /**
     * @var MinifyInterface
     */
    private $minifyService;

    /**
     * GifController constructor.
     * @param Request $request
     * @param MinifyInterface $minifyService
     */
    public function __construct(Request $request, MinifyInterface $minifyService)
    {
        $this->request = $request;
        $this->minifyService = $minifyService;
    }

    /**
     * Get bounds for other methods
     */
    private function getBounds()
    {
        $this->validate(
            $this->request,
            [
                'limit' => ['nullable', 'integer'],
                'offset' => ['nullable', 'integer'],
                'query' => ['nullable', 'string', 'max:255'],
            ]
        );
        $this->limit = (int)$this->request->query('limit', 25);
        $this->offset = (int)$this->request->query('offset', 0);
        if ($this->limit > 25) {
            $this->limit = 25;
        }
    }

    /**
     * Retrieve the gif list
     * @return JsonResponse
     */
    public function index()
    {
        $this->getBounds();
        $data = Gif::select()->orderBy('created_at', 'desc')->skip($this->offset)->limit($this->limit)->get();
        return response()->json(
            [
                'data' => $data,
                'pagination' => [
                    'limit' => $this->limit,
                    'offset' => $this->offset
                ]
            ]
        );
    }

    /**
     * Retrieve the gif list related to the keywords
     * @return JsonResponse
     */
    public function search()
    {
        $this->getBounds();
        $query = $this->request->query('query');
        $keywords = explode(',', $query);

        event(new SearchEvent(Auth::user(), $query));

        $data = Tag::with(
            [
                'gifs' => function ($query) {
                    $query->skip($this->offset)
                        ->limit($this->limit);
                }
            ]
        )->whereIn('tag', $keywords)->get();
        return response()->json(
            [
                'data' => $data,
                'pagination' => [
                    'limit' => $this->limit,
                    'offset' => $this->offset
                ]
            ]
        );
    }

    /**
     * Redirect to the gif
     * @param string $key
     * @return RedirectResponse
     */
    public function get(string $key)
    {
        $gif = Gif::findOrFail($this->minifyService->decode($key));
        return redirect($gif->path);
    }

    /**
     * Encode gifId
     * @param string $gifId
     * @return JsonResponse
     */
    public function share(string $gifId)
    {
        Gif::findOrFail($gifId);
        return response()->json(
            [
                'url' => route('minified', ['key' => $this->minifyService->encode($gifId)])
            ]
        );
    }
}
