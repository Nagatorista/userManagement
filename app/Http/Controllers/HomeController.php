<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use \Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified', ['only' => ['edit']]);
    }
    public function edit():View{
        return view('home.edit');
    }
    public function home(){
        return view('home.home');
    }
    public function update(Request $request): RedirectResponse{
        $rules=[
            'name'              =>              'required|max:255',
            'email'             =>              'required|max:255|email',
            'current_password'  =>              'nullable|current_password',
            'password'          =>              'nullable|min:8|confirmed'
        ];
        $messages = [
            'name.required'                     =>                'El nombre es obligatorio.',
            'name.max'                          =>                'El nombre no puede superar los 255 caracteres.',
            'email.required'                    =>                'El correo electrónico es obligatorio.',
            'email.max'                         =>                'El correo electrónico no puede superar los 255 caracteres.',
            'email.email'                       =>                'Introduce una dirección de correo electrónico válida.',
            'current_password.current_password' =>                'La contraseña actual no es correcta.',
            'password.min'                      =>                'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'                =>                'Las contraseñas no coinciden.'
        ];
        $validator= Validator::make($request->all(), $rules, $messages);
        if($validator->fails()){
            return back()->withInput()->withErrors($validator);
        }
        $user = auth()->user();
        $user->name = $request->name;
        if ($request->email != $user->email){
            $user->email = $request->email;
            $user->email_verified_at = null;
        }
        if($request->password != null && $request->current_password != null){
            $user->password= Hash::make($request->password);
        }
        try{
            $user->save();
            return redirect()->route('home')->with(['success' => 'El usuario se ha modificado correctamente.']);
        } catch(Exception $e){
            return back()->withInput()->withErrors(['error' => 'No se ha podido modificar al usuario.']);
        }
    }
}
