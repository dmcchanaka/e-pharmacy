<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {   
        $input = $request->all();
  
        $validator = Validator::make($request->all(),['username'=>'required|exists:users,username','password'=>'required']);

        if ($validator->fails()) {
            return redirect('login')->withErrors($validator)->withInput();
        }
  
        $fieldType = $request->username;
        if(Auth::attempt(['username'=>$request->username,'password'=>$request->password])){
            $user = Auth::user();
            return redirect()->route('home');
        } else {
            return redirect()->route('login')->withErrors(['password'=>'The Entered Password is incorrect'])->withInput();
        }
          
    }
}
