<?php
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Attendance;


class AttendanceController extends Controller
{
   public function getall(Request $request)
   {
      switch ($request->user()->role->role_type) {
         case "admin":
            $recommendations = Attendance::all();
            return response()->json($recommendations);
         default:
            return response()->json(['error' => 'Unauthorized'], 403);
      }
   }
   public function store(Request $request)
   {
      try {
         $validatedData = $request->validate([
            'user_id' => 'required|exists:User,id',
            'date' => 'required|date',
            'time_in' => 'required|date_format:H:i:s',
            'time_out' => 'required|date_format:H:i:s',
            'status' => 'required|string',
         ]);
         $attendance = Attendance::create($validatedData);
         $attendance->save();
         return response()->json($attendance, 201);
      } catch (Exception $e) {
         return response()->json(['error' => 'Failed to create attendance', 'message' => $e->getMessage()], 400);
      }
   }
   function show($id, Request $request)
   {
      try {
         $attendance = Attendance::where('user_id', $request->user()->id)->find($id);
         if ($attendance == null) {
            return response()->json(['error' => 'Attendance not found'], 404);
         }
         return response()->json($attendance);
      } catch (Exception $e) {
         return response()->json(['error' => 'Failed to retrieve attendance', 'message' => $e->getMessage()], 400);
      }
   }
}