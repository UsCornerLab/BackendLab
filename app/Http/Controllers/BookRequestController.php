<?php
namespace App\Http\Controllers;

use App\Models\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookRequestController extends Controller
{
 
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'id' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20',
            'publisher' => 'required|string|max:255',
            'recommendation' => 'required|string',
        ];

        $validatedData = $request->validate($rules);

        $bookRequestData = $validatedData;
        $bookRequestData['user_id'] = Auth::check() ? Auth::id() : null;

        $bookRequest = BookRequest::create($bookRequestData);

        return response()->json($bookRequest, 201);
    }

    public function show($id)
    {
        $bookRequest = BookRequest::findOrFail($id);

        if (Auth::check()) {
            if ($bookRequest->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }

        return response()->json($bookRequest);
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'sometimes|required|string|max:255',
            'id' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'isbn' => 'sometimes|required|string|max:20',
            'publisher' => 'sometimes|required|string|max:255',
            'recommendation' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:Pending,Approved,Rejected,Delivered',
        ];

        $validatedData = $request->validate($rules);

        $bookRequest = BookRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $bookRequest->update($validatedData);

        return response()->json($bookRequest);
    }

    
    public function destroy($id)
    {
        $bookRequest = BookRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $bookRequest->delete();

        return response()->json(['message' => 'Book request deleted successfully']);
    }
}
