<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Postcategory;
use App\Post;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $postcategories = Postcategory::pluck('name', 'id')->all();

        return view('home', compact('postcategories'));
    }

    public function posts(){

        $posts = Post::all();

        return $posts;
    }
}
