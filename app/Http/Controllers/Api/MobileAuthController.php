<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class MobileAuthController extends Controller
{
    /**
     * Login customer from mobile app
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required', // can be NIP, username, or email from the flutter app
            'password' => 'required',
        ]);

        $employee = \App\Models\Employee::where('email', $request->email)
                        ->orWhere('username', $request->email)
                        ->first();

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        // Create token for this employee
        $token = $employee->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'access_token' => $token,
            'user' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'phone' => $employee->phone,
            ],
        ]);
    }

    /**
     * Logout customer
     */
    public function logout(Request $request)
    {
        // Delete current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Get current employee profile
     */
    public function user(Request $request)
    {
        $employee = $request->user();
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'phone' => $employee->phone,
            ],
        ]);
    }
}
