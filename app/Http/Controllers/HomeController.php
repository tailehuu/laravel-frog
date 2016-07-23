<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class HomeController extends Controller
{
    /*
     * Show a list of content
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('home.index');
    }
}
