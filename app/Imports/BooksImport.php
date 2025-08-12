<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use App\Models\Category;
use App\Models\Origin;
use App\Models\Shelf;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class BooksImport implements ToCollection
{
  public function collection(Collection $rows)
  {
    // Remove header row if needed
    $header = $rows->shift();

    foreach ($rows as $row) {
      // Map expected fields, fill missing with N/A
      $title = $row[0] ?? 'N/A';
      $isbn = $row[1] ?? 'N/A';
      $publisher = $row[2] ?? 'N/A';
      $publicationDate = $row[3] ?? now()->toDateString();
      $accessionNumber = $row[4] ?? 'N/A';
      $categoryName = $row[5] ?? 'N/A';
      $authors = isset($row[6]) ? explode(',', $row[6]) : ['N/A'];
      $genres = isset($row[7]) ? explode(',', $row[7]) : ['N/A'];
      $fromType = $row[8] ?? 'N/A';
      $fromOrgName = $row[9] ?? 'N/A';
      $shelfName = $row[10] ?? 'N/A';
      $shelfNumber = $row[11] ?? 0;
      $copies = $row[12] ?? 1;
      $addedBy = $row[13] ?? 1; // default admin ID

      $book = new Book([
        'title' => $title,
        'ISBN' => $isbn,
        'publisher' => $publisher,
        "publication_date" => $publicationDate,
        "accession_number" => $accessionNumber,
        'added_by' => $addedBy,
        'copies' => $copies,
        'available_copies' => $copies
      ]);

      $origin = Origin::firstOrCreate(
        ['org_name' => $fromOrgName, 'type' => $fromType],
        ['org_name' => $fromOrgName, 'type' => $fromType]
      );
      $book->from = $origin->id;

      $category = Category::firstOrCreate(
        ['category_name' => $categoryName],
        ['category_name' => $categoryName]
      );
      $book->category_id = $category->id;

      $book->save();

      $authorIds = [];
      foreach ($authors as $author) {
        $result = Author::firstOrCreate(['author_name' => trim($author)]);
        $authorIds[] = $result->id;
      }
      $book->authors()->attach($authorIds);

      $genreIds = [];
      foreach ($genres as $genre) {
        $result = Genre::firstOrCreate(['genre_name' => trim($genre)]);
        $genreIds[] = $result->id;
      }
      $book->genres()->attach($genreIds);

      Shelf::create([
        'shelf_name' => $shelfName,
        'shelf_number' => $shelfNumber,
        "book_id" => $book->id
      ]);
    }
  }
}
