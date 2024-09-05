<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    // Login
    public function login(Request $request)
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $user = User::where('email', $email)->first();

            if (!$user || !Hash::check($password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
            }

            // Generate a personal access token for the user
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json(['success' => true, 'message' => 'Login successful!', 'access_token' => $token, 'user_details' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // Login

    // Register
    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validate([
                'fullName' => 'required',
                'phone' => 'nullable',
                'email' => 'required',
                'address' => 'nullable',
                'password' => 'required',
            ]);

            $existingUser = User::where('email', $validatedData['email'])->first();

            if($existingUser){
                return response()->json(['success' => false, 'message' => 'Email already in use'], 400);
            }

            $user = User::create([
                'name' => $validatedData['fullName'],
                'phone' => $validatedData['phone'],
                'email' => $validatedData['email'],
                'address' => $validatedData['address'],
                'password' => $validatedData['password'],
            ]);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registration Completed'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // Register
}
