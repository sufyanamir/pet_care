<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('email');
        $token = Str::random(60);
        $password = $request->input('password');
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password) && $user->email == 'admin@gmail.com') {
            // Create a session for the user
            session(['user_details' => [
                'token' => $token, // Set token value if needed
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
            ]]);

            return response()->json(['success' => true, 'message' => 'Login successful', 'user_details' => session('user_details')]);
        } else {
            // Authentication failed
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user_details');
        $request->session()->regenerate();

        return redirect('/');
    }

}
