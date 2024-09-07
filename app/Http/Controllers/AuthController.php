<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    protected function getFilePath($url) {
        $delimiter = "storage";

        return explode($delimiter, $url)[1];
    }

    public function register(Request $request) {
        try {

            $data = $request->validate([
                "firstName" => 'required|string|max:255',
                "lastName" => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:User',
                'password' => 'required|string|min:8',
                'age' => 'required|integer',
                "address" => 'required|string|max:255',
                "id_photo" => "required|file|max:10240",
            ]);

            if($request->hasFile('id_photo')) {
                $file = $request->file('id_photo');
                $fileName = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('ID_photos', $fileName, 'public');

                $url = Storage::url($filePath);

                $user = User::create([
                    'first_name' => $data['firstName'],
                    'last_name' => $data['lastName'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'age' => $data['age'],
                    'address' => $data['address'],
                    'id_photo_path' => $url,
                ]);

                if ($request->has('role')) {
                    $role = Role::firstOrCreate(['role_type' => $request->role]);
                    $user->role_id = $role->id;
                    $user->save();
                }
            } else {
                return response()->json(['status'=> false,'message' => "ID photo required"], 500);
            }
    

            
    
            return response()->json([
                'status'=> true,
                'message' => 'Registered successfully',
            ], 201);

        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);
        }
    }

    public function updateProfile(Request $request, $id) {
        try {

            $data = $request->validate([
                "firstName" => 'string|max:255',
                "lastName" => 'string|max:255',
                'email' => 'string|email|max:255',
                'password' => 'string|min:8',
                'age' => 'integer',
                "address" => 'string|max:255',
                // "id_photo" => "file|max:10240",
            ]);
            $user = User::find($id);

            if($request->hasFile('id_photo')) {
                $filePath = $this->getFilePath($user->id_photo_path);
            
                if(Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }

                $file = $request->file('id_photo');
                $fileName = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('ID_photos', $fileName, 'public');

                $url = Storage::url($filePath);

                $user->update([
                    'first_name' => $data['firstName'],
                    'last_name' => $data['lastName'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'age' => $data['age'],
                    'address' => $data['address'],
                    'id_photo_path' => $url,
                ]);
            
            } else {
                $user->update([
                    'first_name' => $data['firstName'],
                    'last_name' => $data['lastName'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'age' => $data['age'],
                    'address' => $data['address'],
                ]);
            }
    
            if ($request->has('role')) {
                    $role = Role::firstOrCreate(['role_type' => $request->role]);
                    $user->role_id = $role->id;
                    $user->save();
                }

            
    
            return response()->json([
                'status'=> true,
                'message' => 'User updated successfully',
                "user" => $user,
            ], 201);

        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);
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
                    'status'=> false,
                    'message' => "Email or Password are wrong"
                ]);
            }

            $user = auth()->user();
            // $token = $user->createToken("access_token", expiresAt:now()->addDay())->plainTextToken;

            $user['role'] = $user->role->role_type;

            return response()->json([
                'status'=> true,
                "message"=> "Logged In successfully",
                "user" => $user,
                "token" => $token
            ]);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);

        }
    }

    public function logout(Request $request) {
        try{
            auth()->logout(true);
            return response()->json(['status'=> true, 'message' => "Logged out successfully"], 200);
           
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);
        }
    
    }

    public function getUser(Request $request) {
        $user = auth()->user();
        $user['role'] = $user->role;

        return response()->json([
            'status'=> true,
            "user" => $user,
        ]);
    }

}
