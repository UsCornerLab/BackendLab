<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request) {
        try {

            $data = $request->validate([
                "firstName" => 'required|string|max:255',
                "lastName" => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:User',
                'password' => 'required|string|min:8',
                'age' => 'required|integer',
                "address" => 'required|string|max:255',
                "id_photo_path" => "required|string",
            ]);
    
            $user = User::create([
                'first_name' => $data['firstName'],
                'last_name' => $data['lastName'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'age' => $data['age'],
                'address' => $data['address'],
                'id_photo_path' => $data['id_photo_path'],
            ]);

            
    
            return response()->json([
                'message' => 'Registered successfully',
            ], 201);

        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request) {
        try {
            $data = $request->validate([
                'email'=> 'required|string|email|max:255',
                'password' => 'required|string',
            ]);

            if(!$token = auth()->attempt($data)) {
                return response([
                    'message' => "Email or Password are wrong"
                ]);
            }

            $user = auth()->user();
            // $token = $user->createToken("access_token", expiresAt:now()->addDay())->plainTextToken;

            return response()->json([
                "message"=> "Logged In successfully",
                "user" => $user,
                "token" => $token
            ]);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request) {
        try{
            auth()->logout(true);
            return response()->json(['message' => "Logged out successfully"], 200);
           
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    
    }

}
