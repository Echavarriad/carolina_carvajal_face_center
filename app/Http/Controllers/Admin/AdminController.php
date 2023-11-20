<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller {

    public function index() {
        $users =Admin::all();
        return view('admin.users_admin.index', compact('users'));
    }


    public function create() {
        return view('admin.users_admin.create');
    }


    public function store(AdminRequest $request){
        $data = $request->all();
        $user = Admin::create($data);
        session()->flash('flash.success', 'El usuario se ha creado con éxito');
        return redirect()->route('admins.index');
    }

    public function edit(Admin $admin) { 
        return view('admin.users_admin.edit', compact('admin'));
    }


    public function update(AdminRequest $request, $id) {
        $admin = Admin::findOrFail($id);       
        if (empty($request->password)) {
            $admin->fill($request->except('password'));
        } else {
            $admin->fill($request->all());
        }
        $admin->save();
        session()->flash('flash.success', 'El usuario se actualizó con éxito');
        return redirect()->route('admins.index');
    }

    public function destroy(Admin $admin) {
        if ($admin->id == auth('admin')->user()->id) {
            return redirect()->route('admins.index');
        }
        $admin->delete();
        session()->flash('flash.success', 'El usuario se eliminó con éxito');
        return redirect()->route('admins.index');
    }

     public function profile(Request $request){
        $user = auth()->guard('admin')->user();
        $admin = Admin::find( $user->id);
        if ($request->isMethod('POST')){
            if (!$user->hasRole('SuperAdmin')) {
               $this->setValidate($request);
            }
            
            if (empty($request->password)) {
                $admin->fill($request->except('password'));
            } else {
                $admin->fill($request->all());
            }
            session()->flash('flash.success', 'Perfil actualizado exitósamente.');
            $admin->save();
        }
        return view('admin.users_admin.profile', compact('admin'));
    }

    public function change_password(Request $request){
        $id = auth()->guard('admin')->user()->id;
        $admin = Admin::find($id);
        if ($request->isMethod('POST')){
            $data = $request->all();
            // dd($data);
            if (Hash::check($data['_current_password'], $admin->password)){
                $this->setValidatePassword($request);
                $admin->fill($request->all());
                session()->flash('flash.success', 'La contraseña ha sido cambiada, ingrese nuevamente.');
                $admin->save();
                return redirect()->route('admin.logout');
              }else{
                session()->flash('flash.warning', 'La contraseña actual  no es correcta.');
              }
        }
        return view('admin.users_admin.change_password', compact('admin'));
    }

    public function published() {
        $data = request()->all();
        $object = Admin::find($data['id']);
        $message = '';
        if ($data['status'] == 'true') {
            $message = 'Administrador activo.';
            $object->published = 1;
        } else {
            $message = 'Administrador inactivo.';
            $object->published = 0;
        }
        $response = ['status' => true , 'message'=> $message];
        $object->save();
        return response()->json($response);
    }

    private function setValidate($request){
        $validatedData = $request->validate(
            [
               'name' => 'required',
               'document' => 'required',
               'email' => 'required|email|unique:admins,email,' . $request->id,
            ],
            [
                'name.required' => 'El nombre es requerido',
                'email.required' => 'El email es requerido',
                'email.email' => 'Ingrese un correo válido',
                'email.unique' => 'El correo ingresado ya está registrado',
                'document.required' => 'El documento es requerido',
            ]
        );
    }

    private function setValidatePassword($request){
          $validatedData = $request->validate([
               'password' => 'required|confirmed|min:6'
          ],
          [
            'password.required' => 'La contraseña es requerida',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'Las contraseña debe tener mínimo 6 caracteres',
          ]
      );
    }
}

