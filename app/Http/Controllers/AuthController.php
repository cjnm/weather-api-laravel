<?php

namespace App\Http\Controllers;

use JWTAuth;
use Exception;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function register(Request $request)
    {

        //Validate request data
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed notice if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages(),
            ], Response::HTTP_OK);
        }

        // Create user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * User login
     *
     * @param  Request  $request
     * @return mixed
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //Validate request data
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed notice if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages(),
            ], Response::HTTP_OK);
        }

        try {
            $token = JWTAuth::attempt($credentials);

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid',
                ], Response::HTTP_UNAUTHORIZED);
            }

            return response()->json([
                'success' => true,
                'message' => 'Token created successfully',
                'data' => $token,
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                    'success' => false,
                    'message' => 'Could not create token',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * User logout
     *
     * @param  Request  $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        //Validate token is coming in with request
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed notice if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages(),
            ], Response::HTTP_OK);
        }

        //Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function user()
    {
        return 'Authenticated User';
    }
}
