<?php
namespace App\Http\Controllers;

use App\Models\BookSupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookSupportRequestController extends Controller
{

    public function store(Request $request)
    {
        $rules = [
            'organization_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone_number' => 'required|string|max:15',
            'requested_book_titles' => 'required|array',
            'number_of_books' => 'required|array',
            'request_letter' => 'required|file|max:10240|mimes:pdf,docx,jpeg,png',
        ];

        $validatedData = $request->validate($rules);

    
        if ($request->hasFile('request_letter')) {
            $path = $request->file('request_letter')->store('request_letters', 'public');
            $validatedData['request_letter'] = $path;
        }

        
        $validatedData['requested_book_titles'] = json_encode($validatedData['requested_book_titles']);
        $supportRequest = BookSupportRequest::create($validatedData);

        return response()->json($supportRequest, 201);
    }


    public function review(Request $request, $id)
    {
        $rules = [
            'status' => 'required|in:Approved,Rejected',
            'admin_comments' => 'nullable|string',
        ];

        $validatedData = $request->validate($rules);

        $supportRequest = BookSupportRequest::findOrFail($id);
        $supportRequest->update($validatedData);

        return response()->json(['message' => 'Request reviewed successfully']);
    }
}
