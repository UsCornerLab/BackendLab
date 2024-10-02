<?php

namespace App\Http\Controllers;

use App\Models\SupportedBook;
use Illuminate\Http\Request;

class SupportedBooksController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'support_request_id' => 'required|exists:book_support_requests,id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'publisher' => 'nullable|string|max:255',
            'date_of_approval' => 'nullable|date',
            'delivery_status' => 'required|string|in:Pending,Delivered',
            'number_of_books' => 'required|integer|min:1', 
        ];

        $validatedData = $request->validate($rules);
        $supportedBook = SupportedBook::create($validatedData);

        return response()->json($supportedBook, 201);
    }

    public function index()
    {
        return response()->json(SupportedBook::all());
    }

    public function show($id)
    {
        $supportedBook = SupportedBook::findOrFail($id);
        return response()->json($supportedBook);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'isbn' => 'sometimes|nullable|string|max:50',
            'publisher' => 'sometimes|nullable|string|max:255',
            'date_of_approval' => 'sometimes|nullable|date',
            'delivery_status' => 'sometimes|required|string|in:Pending,Delivered',
            'number_of_books' => 'sometimes|required|integer|min:1',
        ];

        $validatedData = $request->validate($rules);
        $supportedBook = SupportedBook::findOrFail($id);
        $supportedBook->update($validatedData);

        return response()->json($supportedBook);
    }

    public function destroy($id)
    {
        $supportedBook = SupportedBook::findOrFail($id);
        $supportedBook->delete();

        return response()->json(['message' => 'Supported book deleted successfully']);
    }
}
