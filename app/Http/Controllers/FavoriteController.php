<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Gif;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class FavoriteController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * FavoriteController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $gifId
     * @return Response
     */
    public function store(string $gifId)
    {
        $gif = Gif::findOrFail($gifId);
        Favorite::firstOrCreate(['user_id' => Auth::id(), 'gif_id' => $gif->id]);
        return response(['ok' => time()], 200);
    }
}
