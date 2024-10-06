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
            
            'email' => 'required|string|email|max:255',
            'request_letter' => 'required|file|max:10240|mimes:pdf,docx,jpeg,png',
        ];
        $validatedData = $request->validate($rules);
        if ($request->hasFile('request_letter')) {
            $path = $request->file('request_letter')->store('request_letters', 'public');
            $validatedData['request_letter'] = $path;
        }
        $supportRequest = BookSupportRequest::create($validatedData);

        return response()->json($supportRequest, 201);
    }

    public function review(Request $request, $id)//not working
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
    public function approve($id)
    {
        $supportRequest = BookSupportRequest::findOrFail($id);
        $supportRequest->status = 'Approved';
        $supportRequest->save();
        return response()->json([
            'message' => 'Support request approved successfully!',
            'supportRequest' => $supportRequest,
        ]);
    }
    public function rejected($id)
    {
        $supportRequest = BookSupportRequest::findOrFail($id);
        $supportRequest->status = 'rejected';
        $supportRequest->save();
        return response()->json([
            'message' => 'Support request rejected !',
            'supportRequest' => $supportRequest,
        ]);
    }
    public function completeForm(Request $request, $id){

        $validatedData = $request->validate([
            'organization_name' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'requested_book_titles' => 'nullable|json',
            'number_of_books' => 'nullable|integer',
            'admin_comments' => 'nullable|string',
        ]);
        $supportRequest = BookSupportRequest::findOrFail($id);
        if ($supportRequest->status !== 'Approved') {
            return response()->json(['message' => 'Request must be approved before completing the form.'], 400);
        }
        $supportRequest->update($validatedData);
        return response()->json(['message' => 'Details updated successfully!', 'request' => $supportRequest]);
}
}