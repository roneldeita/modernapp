<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{

	protected $view     = 10;
    protected $create 	= 11;
    protected $update 	= 12;
    protected $delete 	= 13;
    
    public function index(){

    	$posts = Post::find(1);
    	$user = User::find(1);

        $update = true;
        $delete = true;

        if (!Gate::allows('access', $this->update)) {

            $update=false;
        }

        if (!Gate::allows('access', $this->delete)) {

            $delete=false;
        }

    	if(Gate::allows('access', $this->view)){

    		return view('admin.post.index', compact('posts', 'user' , 'update', 'delete'));

    	}

    	return redirect('/admincontrol');

    }

    public function data(){

    	$posts = Post::orderBy('id','desc')->paginate(10);

    	return response()->json(array(
            'data'=>$posts,
            'pagination' => (string) $posts->links()
        ));

    }

    public function switch_display(Request $request){

        if(Gate::allows('access', $this->update)){

            $post = Post::findOrFail($request->id);

            $post->display =  ($request->display == "false" ? 0 : 1);

            $post->save();
        }


    }

    public function delete_posts(Request $request){

        if(Gate::allows('access', $this->delete)){

            $delete = Post::destroy($request->ids);

            $response = [ 'msg' => 'Posts Deleted successfully' ];

            return response()->json($response);

        }

    }

}
