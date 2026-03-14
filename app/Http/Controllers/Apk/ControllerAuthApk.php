<?php

namespace App\Http\Controllers\Apk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ControllerAuthApk extends Controller
{
    /**
     * Bypass login user for testing
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Accept any credentials as a bypass login
        $token = 'dummy-testing-token-' . uniqid();

        return response()->json([
            'success' => true,
            'message' => 'Login Bypass successful',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => 999,
                    'name' => 'Testing User',
                    'email' => 'testing@mail.com',
                    'username' => $request->username,
                    'role' => 'admin',
                ],
            ],
        ]);
    }

    /**
     * Logout bypass user
     */
    public function logout(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }
}
