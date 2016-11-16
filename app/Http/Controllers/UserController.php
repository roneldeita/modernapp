<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\User;
use App\Photo;

class UserController extends Controller
{
    
    protected $view     = 4;
    protected $create 	= 5;
    protected $update 	= 6;
    protected $delete 	= 7;

    public function index(){

        $create = true;
        $update = true;
        $delete = true;

        if (!Gate::allows('access', $this->create)) {

            $create = false;
        }

        if (!Gate::allows('access', $this->update)) {

            $update = false;
        }

        if (!Gate::allows('access', $this->delete)) {

            $delete = false;
        }

    	if(Gate::allows('access', $this->view)){

    		return view('admin.user.index', compact('create', 'update', 'delete'));

    	}

    	return redirect('/admincontrol');
    	
    }

    public function data(){

        $users = User::all();

        return response()->json($users);

    }

    public function create(Request $request){

        $input = $request->all();
 
        if (Gate::allows('access', $this->create)) {

            $this->validate($request, [
                'name'  =>'required|min:3|max:32',
                'email' =>'required|email|unique:users',
                'password'  =>'required|min:3|confirmed',
                'password_confirmation' =>'required|min:3',
                'photo_id'  =>'mimes:jpeg,jpg,bmp,png,gif|max:3000|dimensions:min_width=200,min_height=200,max_width=500,max_height=500'
            ]);

        
            //check if photo_id is exists
            if($file = $request->file('photo_id')){

                //naming the photo
                $name = time().'-'.$file->getClientOriginalName();

                //move photo to this location
                $file->move('images/profile_picture', $name);

                //save photo info to Photo table
                $photo = Photo::create(['filename'=> $name]);

                //get the id of inserted photo
                $input['photo_id'] = $photo->id;

            }

            $input['password'] = bcrypt(trim($request->password));

            User::create($input);

            $response = [ 'msg' =>' New user successfully created' ];

            return response()->json($response);

        }
        

    }

     public function update(Request $request){

        if (Gate::allows('access', $this->update)) {

            //password
            if(trim($request->password) == '' ){

                $input =  $request->except('password');

                $this->validate($request, [
                    'name'  =>'required|min:3|max:32',
                    'email' =>'required|email|unique:users,email,'.$request->user_id.',id',
                    'photo_id'  =>'mimes:jpeg,jpg,bmp,png,gif|max:3000|max:3000|dimensions:min_width=200,min_height=200,max_width=500,max_height=500'
                ]);

            }else{
     
               $input = $request->all();

               $this->validate($request, [
                    'name'      =>'required|min:3|max:32',
                    'email'     =>'required|email|unique:users,email,'.$request->user_id.',id',
                    'password'  =>'required|min:3|confirmed',
                    'password_confirmation' =>'required|min:3',
                    'photo_id'  =>'mimes:jpeg,jpg,bmp,png,gif|max:3000|max:3000|dimensions:min_width=200,min_height=200,max_width=500,max_height=500'
                ]);

                $input['password'] = bcrypt($request->password);
            }

            if($file = $request->file('photo_id')){

                $name = time().'-'.$file->getClientOriginalName();

                $file->move('images/profile_picture', $name);

                $photo = Photo::create(['filename' => $name]);

                $input['photo_id'] = $photo->id;
            }

            $user =User::findOrFail($request->user_id);

            $user->update($input);

            $response = [ 'msg' =>' User updated successfully' ];

            return response()->json($response);
        
        }

    }

    public function remove(Request $request){

        if (Gate::allows('access', $this->delete)) {

            $user = User::findOrFail($request->user_id);

            $user->methods()->detach();

            $user->delete();
            
            $response = [ 'msg' =>' User delete successfully' ];

            return response()->json($response);

        }

    }


}
