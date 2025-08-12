<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NewsPostController extends Controller
{

    protected function getFilePath($url)
    {
        $delimiter = "storage";

        return $url ? explode($delimiter, $url)[1] : "";
    }
    // List published news with pagination
    public function index()
    {
        try{
        $news = NewsPost::where('is_published', true)
            ->latest('published_at')
            ->paginate(10);

        return response()->json($news);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Show single published news by ID or slug
    public function show(NewsPost $newsPost)
    {
        try{
        if (!$newsPost->is_published) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json($newsPost);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Store new news post
    public function store(Request $request)
    {
        try{
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('news_posts')],
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'featured_image' => 'sometimes|nullable|file|mimes:jpg,jpeg,png|max:10240',
            'is_published' => 'required|boolean',
            'published_at' => 'nullable|date|required_if:is_published,true',
        ]);

        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('newsPost', $fileName, 'public');

            $fileUrl = Storage::url($filePath);

            $validated['featured_image'] = $fileUrl;

        }

        $validated['created_by'] = $request->user()->id;

        $newsPost = NewsPost::create($validated);

        return response()->json([
            'News' => $newsPost,
            'status' => true,
            'message' => 'News Posted successfully',
        ], 201);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Update existing news post
    public function update(Request $request, NewsPost $newsPost)
    {
        try{
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('news_posts')->ignore($newsPost->id)],
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'featured_image' => 'sometimes|file|mimes:jpg,jpeg,png|max:10240',
            'is_published' => 'required|boolean',
            'published_at' => 'nullable|date|required_if:is_published,true',
        ]);
        if ($request->hasFile('featured_image')) {
            $filePath = $this->getFilePath($newsPost->featured_image);

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $file = $request->file('featured_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('newsPost', $fileName, 'public');

            $fileUrl = Storage::url($filePath);

            $validated['featured_image'] = $fileUrl;
        }

        $validated['updated_by'] = $request->user()->id;

        $newsPost = $newsPost->update($validated);

        return response()->json([
            'News' => $newsPost,
            'status' => true,
            'message' => 'News Updated successfully',
        ], 201);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Soft delete a news post
    public function destroy(NewsPost $newsPost)
    {
        try{
            $filePath = $this->getFilePath($newsPost->featured_image);

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $newsPost = $newsPost->delete();

        return response()->json([
                'status' => true,
                'message' => 'News deleted successfully',
                "News" => $newsPost
            ], 200);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
