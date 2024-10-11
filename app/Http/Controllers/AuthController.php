<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only('login_member');
        
    }

    public function index()
    {
        return view('auth.login');
    }

    
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $credentials = request(['email', 'password']);

        if (auth()->attempt($credentials)) {
            $token = Auth::guard('api')->attempt($credentials);
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'token' => $token
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau Password Salah'
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }



    public function register_member_action(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_member' => 'required',
            'no_hp' => 'required',
            'password' => 'required',
            'email' => 'required|email',
            ]);

        if ($validator->fails()){
            Session::flash('errors', $validator->errors()->toArray());
            return redirect('login_member2');
        }

        $input = $request->all();
        $input['password'] = bcrypt($request->password);
        Member::create($input);

        Session::flash('success', 'Akun Berhasil Dibuat, Silakan Login!');
        return redirect('login_member2');
    }

    public function login_member()
    {
        return view('auth.login_member');
    }


    public function login_member_action(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()){
            session()->flash('status', 'Email atau Password Salah!');
            return redirect('login_member2');
        }

        $credentials = $request->only('email', 'password');
        $member = Member::where('email', $request->email)->first();
        if($member){
            if( Auth::guard('webmember')->attempt($credentials)){
                $request->session()->regenerate();
                return redirect()->route('public');
            }else{
            session()->flash('status', 'Email atau Password Salah!');
            return redirect('login_member2');
            }
        }else{
            session()->flash('status', 'Email atau Password Salah!');
            return redirect('login_member2');
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('login');
    }

    public function logout_member()
    {
        Auth::guard('webmember');
        Session::flush();
        return redirect('home');
    }


}
