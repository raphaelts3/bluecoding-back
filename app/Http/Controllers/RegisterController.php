<?php

namespace App\Http\Controllers;

use App\Services\Token\TokenInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /**
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
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]
        );
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $token = $this->tokenService->generate($user->id);
        return response()->json(
            [
                'token' => $token
            ],
            200
        );
    }
}
