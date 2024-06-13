<?php

namespace App\Http\Controllers\Dash;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function index()
    {
        if(Auth::guard('admin')->check()){
            return redirect()->back();
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:admins,email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            if(auth('admin')->user()->company_id == null){
                return redirect()->route('dashboard.home');
            }else{
                return redirect()->route('dashboard.company.home');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        if (auth('admin')->check()) {
            auth('admin')->logout();
        }
        return redirect()->route('dashboard.login');
    }

}
