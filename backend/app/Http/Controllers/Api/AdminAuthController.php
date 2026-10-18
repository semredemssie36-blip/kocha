<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Services\AuditLogService;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            AuditLogService::log(
                'LOGIN_FAILED',
                'auth',
                "Failed administrative login attempt for email: {$request->email}",
                null,
                null,
                ['attempted_email' => $request->email],
                $request
            );

            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our staff records.'],
            ]);
        }

        if (!$user->is_active) {
            AuditLogService::log(
                'LOGIN_FAILED',
                'auth',
                "Login blocked: Account deactivated ({$user->email})",
                $user,
                $user->id,
                ['reason' => 'account_deactivated'],
                $request
            );

            return response()->json([
                'message' => 'Your staff account has been deactivated. Please contact IT.'
            ], 403);
        }

        // Delete any existing tokens for cleaner state
        $user->tokens()->delete();

        // Create new Sanctum bearer token with role capabilities
        $token = $user->createToken('admin_token', [$user->role])->plainTextToken;

        AuditLogService::log(
            'LOGIN',
            'auth',
            "User {$user->name} ({$user->role}) logged into the administrative portal",
            $user,
            $user->id,
            ['role' => $user->role],
            $request
        );

        return response()->json([
            'message' => 'Authentication successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
                'avatar_path' => $user->avatar_path,
                'department_id' => $user->department_id,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            AuditLogService::log(
                'LOGOUT',
                'auth',
                "User {$user->name} ({$user->role}) logged out",
                $user,
                $user->id,
                null,
                $request
            );
        }
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    /**
     * Actor profile update (Name, Email, Phone, Avatar)
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'avatar_path' => 'nullable|string',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
                'avatar_path' => $user->avatar_path,
                'department_id' => $user->department_id,
            ]
        ]);
    }

    /**
     * Actor self password change (Requires current password verification)
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'The current password you provided is incorrect.'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Your password has been changed successfully.'
        ]);
    }
}
