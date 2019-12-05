<?php

namespace App\Http\Controllers;

use App\History;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class HistoryController extends BaseController
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
     * HistoryController constructor.
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
        $data = History::select(['query', 'created_at', 'updated_at'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')->skip($this->offset)->limit($this->limit)->get();
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
