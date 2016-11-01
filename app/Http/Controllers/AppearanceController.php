<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Theme;


class AppearanceController extends Controller
{
    private $view = 8;
    private $change_theme = 9;
    
    function index(){

        $change_theme = true;

        if (Gate::allows('access', $this->change_theme)) {

            $change_theme = false;

        }
        
        if (Gate::allows('access', $this->view)) {

        	$themes = Theme::all();

        	return view('admin.appearance.index', compact('themes', 'change_theme'));

        }

        return redirect('/admincontrol');

    }

    function user_theme(){

    	$user = $user = Auth::user();

    	return response()->json($user->theme);

    }

    function change_theme(Request $request){

        if (Gate::allows('access', $this->change_theme)) {

        	$selected = $request->theme_id;

        	$user = Auth::user();

        	$user->theme_id=$selected;

    		$update = $user->update();

        	return response()->json($update);
        }

    }

}
