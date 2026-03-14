<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponses;

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:customers',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $customer = Customer::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $customer->createToken('auth-token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user'  => $customer,
        ], 'Registration successful', 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required_without:username|string',
            'username' => 'required_without:email|string', // NIP
            'password' => 'required|string',
        ]);

        $login = $request->input('email') ?: $request->input('username');
        
        // Smarter NIP detection: if it's 10+ digits or specified as username, it's potentially an employee
        $isNip = $request->has('username') || (is_numeric($login) && strlen($login) >= 10);

        $user = null;
        if ($isNip) {
            $user = Employee::where('username', $login)->orWhere('nip', $login)->first();
        } 
        
        // If not found or not NIP, try Customer email
        if (!$user) {
            $user = Customer::where('email', $login)->first();
        }

        // If still not found, try Employee email
        if (!$user) {
            $user = Employee::where('email', $login)->first();
        }

        // Final fallback: check core User model (for admin/staff login)
        if (!$user && class_exists('\App\Models\User')) {
            $user = \App\Models\User::where('email', $login)->orWhere('username', $login)->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Invalid credentials', 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        // Custom response to match Flutter app expectations
        return response()->json([
            'success'      => true,
            'message'      => 'Login successful',
            'access_token' => $token,
            'user'         => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? ($user instanceof \App\Models\Customer ? 'customer' : 'employee'),
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully');
    }

    public function profile(Request $request)
    {
        return $this->success($request->user(), 'Profile retrieved successfully');
    }
}
