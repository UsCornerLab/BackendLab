<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;
use App\Models\NewsPost;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    public function dashboardStats()
    {
        try {
            $stats = [
                'total_books' => Book::count(),
                'total_users' => User::count(),
                'total_news' => NewsPost::count(),
                'total_categories' => Category::count(),
                'deactivated_books' => Book::where('is_active', false)->count(),
                'deactivated_users' => User::where('verified', false)->count(),
            ];

            return response()->json([
                'status' => true,
                'data' => $stats
            ], 200);
        } catch (\Exception $e) {
            Log::error('Dashboard stats error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve dashboard statistics'
            ], 500);
        }
    }
}
