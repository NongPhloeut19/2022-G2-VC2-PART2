<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authentication extends Controller
{
    // https://jwt-auth.readthedocs.io/en/develop/laravel-installation/
    // https://jwt-auth.readthedocs.io/en/develop/quick-start/

    /**
     * Login user and return a token,'token'=>$token
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = auth()->guard('api')->attempt($credentials)) {
            $user = auth()->user();
            $role = $user->role;
            $first_name = $user->first_name;
            $last_name = $user->last_name;
            $attendances = $user->attendances;
            // $subjects = $user->subjects;
            // $scores = $user->scores;
            // $scores = $user->scores;
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $user,
                    'role' => $role,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    // 'data' => auth()->user(),
                    'attendance' => $attendances,
                    // 'subject' => $subjects,
                    // 'score' => $scores,
                    'access_token' => $token
                ],
                200
            )->header('Authorization', $token);
        }
        return response()->json(['error' => 'login_error'], 401);
    }


    /**
     * Logout User
     */

    public function logout()
    {
        auth()->user()->delete;
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out Successfully.'
        ], 200);
    }

    /**
     * Get authenticated user
     */

    public function user(Request $request)
    {
     
        $user = User::with('guardian','classroom','attendances', 'scores',)->find(Auth::user()->id);
        
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }
}
