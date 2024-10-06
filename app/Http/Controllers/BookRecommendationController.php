<?php

namespace App\Http\Controllers;

use App\Models\BookRecommendation;
use Illuminate\Http\Request;

class BookRecommendationController extends Controller
{
    /**
     * Display a listing of the book recommendations.
     *
     * @return \Illuminate\Http\Response
     */
    public function getall()
    {
        $recommendations = BookRecommendation::all();
        return response()->json($recommendations);
    }

    /**
     * Store a newly created book recommendation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'book_title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'reason' => 'required|string',
                'status' => 'required|string',
                'publisher' => 'nullable|string|max:255',
            ]);

            $recommendation = BookRecommendation::create($validatedData);

            return response()->json($recommendation, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create book recommendation', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified book recommendation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recommendation = BookRecommendation::findOrFail($id);
        return response()->json($recommendation);
    }

    /**
     * Update the specified book recommendation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'reason' => 'required|string',
            'status' => 'required|string',
            'publisher' => 'nullable|string|max:255',
        ]);

        $recommendation = BookRecommendation::findOrFail($id);
        $recommendation->update($validatedData);

        return response()->json($recommendation);
    }

    /**
     * Remove the specified book recommendation from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $recommendation = BookRecommendation::findOrFail($id);
        $recommendation->delete();

        return response()->json(null, 204);
    }
}