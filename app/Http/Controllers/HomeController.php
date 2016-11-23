<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Postcategory;
use App\Post;
use App\Events\PostEvent;

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

        $posts = Post::where('display','=',1)->orderBy('id','desc')->paginate(10);

        return response()->json($posts);
    }

    public function show_comments(Request $request){

        $post = Post::findOrFail($request->id);

        return $post->comments;

    }

    public function create_post(Request $request){

        $this->validate($request, [
            'postcategory_id' => 'required',
            'body' => 'required'
            ]);

        $user = Auth::user();
        
        $id = $user->posts()->create($request->all())->id;

        $response = [ 'id' => $id ];

        $new_post = Post::find($id);

       // event(new PostEvent($new_post));

        broadcast(new PostEvent($new_post))->toOthers();

        return response()->json($response);

    }

    public function inserted(){

        $post_id = Input::get('id');

        $post = Post::findOrFail($post_id);

        return response()->json($post);

    }

    public function load_new_posts(){

        $post_ids = Input::get('ids');

        $posts = Post::findMany($post_ids);

        return response()->json($posts);

    }

    public function messages()
    {
        return [
            'postcategory_id.required' => 'The About field is required',
            'body.required'  => 'A message is required',
        ];
    }
}
