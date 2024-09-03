<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    public function serveFile($fileName)
    {
        if (Storage::disk('public')->exists('ID_photos/' . $fileName)) {
            return response()->download(storage_path('app/public/ID_photos/' . $fileName));
        }

        return abort(404, 'File not found');
    }
}
