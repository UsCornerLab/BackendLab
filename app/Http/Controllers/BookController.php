<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Shelf;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class BookController extends Controller
{
    public function create(Request $request) {
       try {
         $data = $request->validate([
            "title"=> "required|string|max:255",
            "ISBN"=> "required|string|max:255",
            "publisher"=> "required|string|max:255",
            "publication_date"=> "required|date",
            "cover_image"=> "file|max:10240",
            "accession_number" => "required|string|max:255",
            "category"=> "required|string",
            "author"=> "required|array",
            "genre"=> "required|array",
            "from_type" => "required|string|max:255",
            "shelf_name" => "required|string|max:255",
            "shelf_number" => "required|integer",
            "added_by" => "required|string|max:255",
        ]);

        $book = new Book([
            'title' => $data['title'],
            'ISBN'=> $data['ISBN'],
            'publisher'=> $data['publisher'],
            "publication_date" => $data['publication_date'],
            "accession_number" => $data['accession_number'],
            'added_by'=> $data['added_by'],
        ]);
        $origin = $book->origin()->create([
            'org_name' => $request->org_name,
            'type' => $data['from_type'],
        ]);
        $category = $book->category()->createOrFirst(['category_name' => $data['category']]);

        $book->from = $origin->id;
        $book->category_id = $category->id;

       
        $authorId = [];
        $genreId = [];

        foreach($data['author'] as $author) {
            $result = Author::createOrFirst(['author_name' => $author]);
            array_push( $authorId, $result->id );
        }
        foreach($data['genre'] as $genre) {
            $result = Genre::createOrFirst(['genre_name' => $genre]);
            array_push( $genreId, $result->id );
        }

       

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $fileName = now().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('bookCovers', $fileName, 'public');
            
            $book->cover_image_path = $filePath;

        }
        $book->save();

        
        $book->authors()->attach($authorId);
        $book->genres()->attach($genreId);
        
        Shelf::create([
            'shelf_name'=> $data['shelf_name'],
            'shelf_number'=> $data['shelf_number'],
            "book_id" => $book->id
        ]);

       return response()->json([
                'status'=> true,
                'message' => 'Book stored successfully',
            ], 201);

       } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);

        }
    }


    public function getAll(Request $request) {
       try {
         $books = Book::with(['authors' => function ($query) {
            $query->select('Authors.author_name');
         },
         'genres' => function ($query) {
            $query->select('Genre.genre_name');
         },
         'category' => function ($query) {
            $query->select('Category.id', 'Category.category_name');
         },
         "shelf" => function ($query) {
            $query->select('Shelf_book.id', 'Shelf_book.book_id', 'Shelf_book.shelf_name', 'Shelf_book.shelf_number');
         }])->get();

         return response()->json([
                'status'=> true,
                'message' => 'Books fetched successfully',
                "books" => $books
            ], 200);
       } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);

        }
       

    }

    public function delete($id) {
        try {
            $book = Book::find($id);

            if(!$book) {
                return response()->json([
                    'status'=> false,
                    'message' => "No Book found the id $id",
                ], 404);
            }
             $book->delete();

            return response()->json([
                    'status'=> true,
                    'message' => 'Books deleted successfully',
                    "books" => $book
                ], 200);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);

        }

    }
}