<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\User;

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

        if (Gate::allows('access', $this->create)) {

            $this->validate($request, [
                'name'  =>'required|min:3|max:32',
                'email' =>'required|email|unique:users',
                'password'  =>'required|min:3|confirmed',
                'password_confirmation' =>'required|min:3',
            ]);

            $request['password'] = bcrypt(trim($request->password));

            User::create($request->all());

            $response = [ 'msg' =>' User created successfully' ];

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
                    'email' =>'required|email|unique:users,email,'.$request->user_id.',id'
                ]);

            }else{
     
               $input = $request->all();

               $this->validate($request, [
                    'name'      =>'required|min:3|max:32',
                    'email'     =>'required|email|unique:users,email,'.$request->user_id.',id',
                    'password'  =>'required|min:3|confirmed',
                    'password_confirmation' =>'required|min:3',
                ]);

                $input['password'] = bcrypt($request->password);
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
