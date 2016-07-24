<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DownloadController extends Controller
{
    /**
     * Download file
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request['id'];
        $path = \Crypt::decrypt($id);
        $contents = \Storage::get($path);
        $mimeType = \Storage::mimeType($path);

        return (new Response($contents, 200))->header('Content-Type', $mimeType);
    }
}
