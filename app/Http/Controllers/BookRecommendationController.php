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
                'user_id' => 'required|exists:User,id',
                'book_title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'reason' => 'required|string',
                'publisher' => 'nullable|string|max:255',
            ]);
            $validatedData['status'] = 'pending';

            $recommendation = BookRecommendation::create($validatedData);
            $recommendation->save();
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
        try {
            $recommendation = BookRecommendation::findOrFail($id);
            return response()->json($recommendation);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Book recommendation not found', 'message' => $e->getMessage()], 404);
        }
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
        try {
            $recommendation = BookRecommendation::findOrFail($id);

            $validatedData = $request->validate([
                'book_title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'reason' => 'required|string',
                'status' => 'required|string',
                'publisher' => 'nullable|string|max:255',
            ]);

            // Fill empty fields with their previous data
            foreach ($validatedData as $key => $value) {
                if (empty($value)) {
                    $validatedData[$key] = $recommendation->$key;
                }
            }

            $recommendation->update($validatedData);

            return response()->json($recommendation);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Book recommendation not found', 'message' => $e->getMessage()], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update book recommendation', 'message' => $e->getMessage()], 400);
        }
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