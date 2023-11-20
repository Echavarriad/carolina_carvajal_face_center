<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Section;

class SectionController extends Controller {
    private $model = 'App\Models\Section';
    private $name = 'sections';

    public function __construct() {
        View::share('name', strtolower($this->name));
    }

    public function index() {
        $records = $this->model::all();
        return view('admin.' .$this->name . '.index', compact('records'));
    }

    public function create() {
        return view('admin.' .$this->name . '.create');
    }

    public function store() {
        $data = request()->except('_token');
        $this->model::create($data);
        session()->flash('flash.success', 'Registro creado con éxito');
        return redirect()->route($this->name . '.index');
    }



    public function edit($id) {
        $section = $this->model::findOrFail($id);
        return view('admin.' .$this->name . '.edit', compact('section'));
    }



    public function update(Request $request, $id) {

        $section = $this->model::findOrFail($id);
        $section->fill($request->all());
        $section->save();
        session()->flash('flash.success', 'Registro actualizado con éxito');
        return redirect()->route($this->name . '.index');
    }

     public function show(){
          $data = request()->all();
          $section = Section::findOrFail($data['section']);
          if ($data['status'] == 'true') {
               $section->show = 1;
          } else {
               $section->show = 0;
          }
               $section->save();
          
          return response()->json(['status' => true , 'message'=>'Actualización realizada']);
     }



}

