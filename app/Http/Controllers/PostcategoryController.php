<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Input;
use App\Postcategory;

class PostcategoryController extends Controller
{

    protected $view     = 14;
    protected $create 	= 15;
    protected $update 	= 16;
    protected $delete 	= 17;
    
    public function index(){

    	$create = true;
        $update = true;
        $delete = true;

        if (!Gate::allows('access', $this->create)) {

           $create=false;
        }

        if (!Gate::allows('access', $this->update)) {

            $update=false;
        }

        if (!Gate::allows('access', $this->delete)) {

            $delete=false;

        }

    	if(Gate::allows('access', $this->view)){

    		return view('admin.post.category.index', compact('create', 'update', 'delete'));

    	}

    	return redirect('/admincontrol');

    }

    public function data(){

        $search = Input::get('search');

    	$categories = Postcategory::where('name','like', '%'.$search.'%')->orderBy('id','desc')->paginate(5);

    	return response()->json(array( 
            'data'=> $categories, 
            'pagination' => (string) $categories->links()
        ));

    }

    public function create(Request $request){

         if (Gate::allows('access', $this->create)) {

            $this->validate($request, [
                'name' => 'required|min:3|max:32'
            ]);

            Postcategory::create($request->all());

            $response = [ 'msg' => 'New Category for Post created successfully' ];

            return response()->json($response);

         }

    }

    public function update(Request $request){

        if (Gate::allows('access', $this->update)) {

            $this->validate($request, [
                'name' => 'required|min:3|max:32|unique:postcategories,name,'.$request->postcategory_id.',id'
            ]);

            $postcategory = Postcategory::findOrFail($request->postcategory_id);

            $postcategory->update($request->all());

            $response = [ 'msg' => 'Post Category Updated successfully' ];

            return response()->json($response);

        }

    }

}
