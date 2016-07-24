<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{
    /*
     * Show a list of content
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $path = $request['id'];
        $path = empty($path) ? Crypt::encrypt('') : $path;

        $frogStorage = new \App\Frog\Storage();
        $files = $frogStorage->files($path);

        return view('home.index', [
            'path' => Crypt::decrypt($path),
            'files' => $files,
        ]);
    }
}
