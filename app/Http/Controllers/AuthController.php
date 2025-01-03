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

        return $url ? explode($delimiter, $url)[1] : "";
    }

    public function register(Request $request) {
        try {

            $data = $request->validate([
                "firstName" => 'required|string|max:255',
                "lastName" => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:User',
                'password' => 'required|string|min:8',
                'birthDate' => 'required|date',
                "address" => 'required|string|max:255',
                "id_photo" => "required|file|mimes:jpg,png,jpeg|max:10240",
                "profile" => "sometimes|image|mimes:jpg,png,jpeg|max:10240",
                "verified" => "sometimes|boolean"

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
                    'birthDate' => $data['birthDate'],
                    'address' => $data['address'],
                    'id_photo_path' => $url,
                ]);


                if($request->hasFile('profile')) {
                    $file = $request->file('profile');
                    $fileName = time().'_'.$file->getClientOriginalName();
                    $filePath = $file->storeAs('profiles', $fileName, 'public');

                    $url = Storage::url($filePath);

                    $user->profile = $url;
                    
                }


                if ($request->has('role')) {
                    $role = Role::firstOrCreate(['role_type' => $request->role], ['role_type' => $request->role]);
                    $user->role_id = $role->id;
                }

                if ($request->has('verified')) {
                    $user->verified = $request->verified;
                }

                $user->save();
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
                "firstName" => 'sometimes|string|max:255',
                "lastName" => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255',
                'password' => 'sometimes|string|min:8',
                'birthDate' => 'sometimes|date',
                "address" => 'sometimes|string|max:255',
                "role" => 'sometimes|string|max:225',
                "id_photo" => "sometimes|file|mimes:jpg,png,jpeg|max:10240",
                "profile" => "sometimes|file|mimes:jpg,png,jpeg|max:10240",

            ]);

            

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->input('password'));
            }
            if ($request->filled("firstName")) {
                $data["first_name"] = $data["firstName"];
                unset($data['firstName']);
            }
            if ($request->filled("lastName")) {
                $data["last_name"] = $data["lastName"];
                unset($data['lastName']);
            }
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

                $allData = array_merge($data, ['id_photo_path' => $url]);
                $user->update($allData);
            
            } else {
                $user->update($data);
            }

            if($request->hasFile('profile')) {
                $filePath = $this->getFilePath($user->profile);
            
                if(Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }

                $file = $request->file('profile');
                $fileName = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('profiles', $fileName, 'public');

                $url = Storage::url($filePath);

                $user->profile = $url;
            
            }
    
            if ($request->has('role')) {
                    $role = Role::firstOrCreate(['role_type' => $request->role], ['role_type' => $request->role]);
                    $user->role_id = $role->id;
                }
                
            $user->save();
            
    
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
            auth()->logout();
            return response()->json(['status'=> true, 'message' => "Logged out successfully"], 200);
           
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);
        }
    
    }

    public function getUser(Request $request) {
        

        try{
            $user = auth()->user();

            $user['role'] = $user->role;

            return response()->json([
                'status'=> true,
                "user" => $user,
            ]);
           
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);
        }
    }

    public function getUsers(Request $request) {
        

        try{
            $users = User::with([
                "role" => function ($query) {
                $query->select('id', 'role_type');
            }])->get();

            return response()->json([
                'status'=> true,
                "users" => $users,
            ]);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);
        }
    }

    public function verifyUser(Request $request, $id) {
        try {
            $user = User::find($id);

            $user->verified = true;
            $user->save();
            
            return response()->json(['status' => true, "message" => "user successfully verified"]);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);
        }
    }
}
