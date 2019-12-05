<?php

namespace App\Http\Controllers;

use App\Services\Token\TokenInterface;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    /**
     * The request instance.
     *
     * @var Request
     */
    private $request;
    /**
     * @var TokenInterface
     */
    private $tokenService;

    /**
     * Create a new controller instance.
     *
     * @param Request $request
     * @param TokenInterface $tokenService
     */
    public function __construct(Request $request, TokenInterface $tokenService)
    {
        $this->request = $request;
        $this->tokenService = $tokenService;
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @return mixed
     * @throws ValidationException
     */
    public function authenticate()
    {
        $this->validate(
            $this->request,
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        $user = User::where('email', $this->request->input('email'))->first();
        if ($user && Hash::check($this->request->input('password'), $user->password)) {
            $token = $this->tokenService->generate($user->id);
            return response()->json(
                [
                    'token' => $token
                ],
                200
            );
        }

        return response()->json(
            [
                'error' => 'Email or password is wrong.'
            ],
            400
        );
    }

    /**
     * Logout a user
     */
    public function logout()
    {
        $this->tokenService->invalidate($this->request->bearerToken());
        return response()->json([], 200);
    }
}
