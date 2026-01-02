<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $employee = Employee::where('username', $request->username)
            ->where('is_active', true)
            ->first();

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create token
        $token = $employee->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $employee->id,
                    'username' => $employee->username,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'image' => $employee->image ? asset('storage/' . $employee->image) : null,
                    'position' => $employee->position ? $employee->position->name : null,
                ],
            ],
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Get user profile
     */
    public function profile(Request $request)
    {
        $employee = $request->user();
        $employee->load(['position', 'rank', 'employeeStatus', 'education']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $employee->id,
                'username' => $employee->username,
                'name' => $employee->name,
                'name_with_title' => $employee->name_with_title,
                'email' => $employee->email,
                'nip' => $employee->nip,
                'phone' => $employee->phone,
                'mobile' => $employee->mobile,
                'image' => $employee->image ? asset('storage/' . $employee->image) : null,
                'position' => $employee->position ? [
                    'id' => $employee->position->id,
                    'name' => $employee->position->name,
                ] : null,
                'rank' => $employee->rank ? [
                    'id' => $employee->rank->id,
                    'name' => $employee->rank->name,
                    'label' => $employee->rank->label,
                ] : null,
                'status' => $employee->employeeStatus ? [
                    'id' => $employee->employeeStatus->id,
                    'name' => $employee->employeeStatus->name,
                ] : null,
                'education' => $employee->education ? [
                    'id' => $employee->education->id,
                    'name' => $employee->education->name,
                ] : null,
            ],
        ]);
    }
}
