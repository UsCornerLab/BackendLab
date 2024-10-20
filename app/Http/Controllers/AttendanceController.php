<?php
namespace App\Http\Controllers;
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

      try {
         $recommendations = Attendance::all();
         return response()->json($recommendations, 200);
      } catch (Exception $e) {
         return response()->json(['error' => 'Failed to retrieve attendances', 'message' => $e->getMessage()], 400);
      }
      
      
   }
   public function store(Request $request)
   {
      try {
         $validatedData = $request->validate([
            'user_id' => 'required|exists:User,id',
            'status' => 'required|string',
         ]);
         $validatedData['date'] = now()->toDateString();
         $validatedData['time_in'] = now()->toTimeString();
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
   public function makestatus($id, Request $request)
   {
      try {
         $attendance = Attendance::where('user_id', $request->user()->id)->find($id);
         if ($attendance == null) {
            return response()->json(['error' => 'Attendance not found'], 404);
         }
         $attendance->status = $request->status;
         $attendance->save();
         return response()->json($attendance, 200);
      } catch (Exception $e) {
         return response()->json(['error' => 'Failed to update attendance', 'message' => $e->getMessage()], 400);
      }
   }
}