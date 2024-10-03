<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Shelf;
use App\Models\borrow;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class BookController extends Controller
{

     protected function getFilePath($url) {
        $delimiter = "storage";

        return explode($delimiter, $url)[1];
    }

    public function create(Request $request) {
       try {
         $data = $request->validate([
            "title"=> "required|string|max:255",
            "ISBN"=> "sometimes|nullable|string|max:255",
            "publisher"=> "required|string|max:255",
            "publication_date"=> "required|date",
            "cover_image"=> "sometimes|nullable|file|mimes:jpg,jpeg,png|max:10240",
            "accession_number" => "required|string|max:255",
            "category"=> "required|string",
            "author"=> "required|array",
            "genre"=> "required|array",
            "from_type" => "required|string|max:255",
            "shelf_name" => "required|string|max:255",
            "shelf_number" => "required|integer",
            "added_by" => "required|integer|max:255",
        ]);

        $book = new Book([
            'title' => $data['title'],
            'ISBN'=> $data['ISBN'],
            'publisher'=> $data['publisher'],
            "publication_date" => $data['publication_date'],
            "accession_number" => $data['accession_number'],
            'added_by'=> $data['added_by'],
        ]);
        $origin = $book->origin()->createOrFirst([
            'org_name' => $request->from_org_name,
            'type' => $data['from_type'],
        ]);
        $book->from = $origin->id;

        $category = $book->category()->createOrFirst(['category_name' => $data['category']]);
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
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('bookCovers', $fileName, 'public');

            $fileUrl = Storage::url($filePath);

            $book->cover_image_path = $fileUrl;

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


    public function getAll() {
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
         }, "origin" => function ($query) {
            $query->select('Origin_from.id', 'Origin_from.org_name', 'Origin_from.type');
         },
         "added_by"])->get();

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

    public function getOne($id) {
       try {
         $book = Book::with([
            'authors' => function ($query) {
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
         },
         "origin" => function ($query) {
            $query->select('Origin_from.id', 'Origin_from.org_name', 'Origin_from.type');
         }, 'added_by'])->find($id);

         return response()->json([
                'status'=> true,
                'message' => 'Books fetched successfully',
                "book" => $book
            ], 200);
       } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);

        }


    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
            "title" => "required|string|max:255",
            "ISBN" => "required|nullable|string|max:255",
            "publisher" => "required|string|max:255",
            "publication_date" => "required|date",
            "cover_image" => "sometimes|file|mimes:jpg,jpeg,png|max:10240",
            "accession_number" => "required|string|max:255",
            "category" => "required|string",
            "author" => "required|array",
            "genre"=> "required|array",
            "from_type" => "required|string|max:255",
            "shelf_name" => "required|string|max:255",
            "shelf_number" => "required|integer",
            "added_by" => "required|integer|max:255",
        ]);

        $book = Book::findOrFail($id);

        $book->title = $data['title'];
        $book->ISBN = $data['ISBN'];
        $book->publisher = $data['publisher'];
        $book->publication_date = $data['publication_date'];
        $book->accession_number = $data['accession_number'];
        $book->added_by = $data['added_by'];

        if ($request->hasFile('cover_image')) {
            $filePath = $this->getFilePath($book->cover_image_path);

            if(Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
                $file = $request->file('cover_image');
                $fileName = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('bookCovers', $fileName, 'public');

                $fileUrl = Storage::url($filePath);

                $book->cover_image_path = $fileUrl;

        }

        $category = $book->category()->createOrFirst(['category_name' => $request->category]);
        $book->category_id = $category->id;

        $genreId = [];
        foreach($data['genre'] as $genre) {
            $result = Genre::createOrFirst(['genre_name' => $genre]);
            array_push( $genreId, $result->id );
            $book->genres()->sync($genreId);
        }

        $authorId = [];
        foreach($data['author'] as $author) {
            $result = Author::createOrFirst(['author_name' => $author]);
            array_push( $authorId, $result->id );
            $book->authors()->sync($authorId);
        }

        $book->origin()->update([
                'org_name' => $request->from_org_name,
                'type' => $data['from_type'],
            ]);

        Shelf::where("book_id", $book->id)->update([
                'shelf_name'=> $data['shelf_name'],
                'shelf_number'=> $data['shelf_number']
            ]);

        $book->save();

        return response()->json([
                'status'=> true,
                'message' => 'Book updated successfully',
                "books" => $book
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

            $filePath = $this->getFilePath($book->cover_image_path);

            if(Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
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
    public function search(Request $request)
    {

        $validated = $request->validate([
            'title' => 'string|nullable',
            'author' => 'string|nullable',
            'category' => 'string|nullable',
            'status' => 'in:available,borrowed,reserved|nullable',
            'page' => 'integer|nullable',
            'limit' => 'integer|nullable'
        ]);


        $query = Book::query();


        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        if ($request->has('author')) {
            $author = $request->input('author');
            $query->whereHas('authors', function ($q) use ($author) {
                $q->where('author_name', 'like', '%' . $author . '%');
            });
        }

        if ($request->has('category')) {
            $category = $request->input('category');
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('category_name', 'like', '%' . $category . '%');
            });
        }

        if ($request->has('status')) {
            $status = $request->input('status');
            $query->where('status', $status);
        }


        $page = $request->input('page', 1); // Default to page 1 if not provided
        $limit = $request->input('limit', 10); // Default to 10 items per page if not provided

        $books = $query->paginate($limit, ['*'], 'page', $page);


        return response()->json($books);
    }

    public function activate(Request $request, $id) {
        try {
            $book = Book::find($id);

            $book->active = true;
            $book->save();

            return response()->json(["status" => true, "message" => "book activated successfully"]);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);
        }

    }

    public function deactivate(Request $request, $id) {
        try {
            $book = Book::find($id);

            $book->active = false;
            $book->save();

            return response()->json(["status" => true, "message" => "book deactivated successfully"]);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);
        }

    }

    public function getCategories(Request $request) {
        try {
            $categories = Category::all();

            return response()->json(['status' => true, "categories" => $categories]);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);
        }

    }

    public function getAuthors(Request $request) {
        try {
            $authors = Author::all();

            return response()->json(['status' => true, "authors" => $authors]);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);
        }

    }

    public function featuredBook() {
        try{
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
         }, "origin" => function ($query) {
            $query->select('Origin_from.id', 'Origin_from.org_name', 'Origin_from.type');
         },
         "added_by"])->orderByDesc('created_at')->take(12)->get();

            return response()->json(["status" => true, "books" => $books]);
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return response()->json(['status'=> false,'message' => $e->getMessage()], 500);
        }
    }
    public function borrow(Request $request,$id)
{
    $user = auth()->user();
    if ($user) {
        if ($user->verified) {
            $book = Book::find($id);
            $status = $book->checkStatus(); 
            if ($status == "Available") {
                $borrowRecord = new Borrow();
                $borrowRecord->user_id = $user->id;
                $borrowRecord->copy_id = $id; 
                $borrowRecord->status = 'Borrowed'; 
                $borrowRecord->save(); 
                $book->status = 'Borrowed';
                $book->save();

                return response()->json([
                    'status' => true,
                    'message' => 'You have successfully borrowed the book.',
                    'borrow_record' => $borrowRecord, 
                ]);}
            else{
                return response()->json([
                    'status' => true,
                    'message' => 'You can borrow books but the book is not Available .',
                ]);
            }         
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You must verify your account to borrow books.',
            ], 403); 
        }
    } else {
        return response()->json([
            'status' => false,
            'message' => 'User not authenticated.',
        ], 401); // Unauthorized
    }
}

}