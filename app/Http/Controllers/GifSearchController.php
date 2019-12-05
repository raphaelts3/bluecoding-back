<?php

namespace App\Http\Controllers;

use App\Events\SearchEvent;
use App\Gif;
use App\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class GifSearchController extends BaseController
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
     * GifSearchController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->getBounds();
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
}
