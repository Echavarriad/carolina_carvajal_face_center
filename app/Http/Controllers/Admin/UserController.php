<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = DB::select('select document from users where document = ?', [$request->document]);
        if($user){
            session()->flash('flash.error', 'El usuario ya exite');
        }else{
             DB::insert('insert into users (document,email) values (?,?)', [$request->document, $request->document.'@gmail.com']);
            session()->flash('flash.success', 'El usuario se ha creado con éxito');
            
        }
        return redirect()->route('users.index');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request,$id)
    {
        $user = User::findOrFail($id); 
        if (empty($request->password)) {
            $user->fill($request->except('password'));
        } else {
            $user->fill($request->all());
        }
        $user->save();
        session()->flash('flash.success', 'El usuario se actualizó con éxito');
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id == auth('admin')->user()->id) {
            return redirect()->route('users.index');
        }
        $user->delete();
        session()->flash('flash.success', 'El usuario se eliminó con éxito');
        return redirect()->route('users.index');
    }
}
