<?php



namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class StatusController extends Controller {

    private $model = 'App\Models\Status';
    private $name = 'status';
    private $singular = 'Estado';
    private $plural = 'Estados';

     public function __construct() {
          View::share('name', strtolower($this->name));
          View::share('singular', $this->singular);
          View::share('plural', $this->plural);
     }

     public function index() {
          $records = $this->model::all();
          return view('admin.'.$this->name . '.index', compact('records'));
     }

     public function create() {
          return view('admin.'.$this->name . '.create');
     }

     public function store(Request $request) {
          $data = $request->all();
          $this->setValidate($request);
          $this->model::create($data);
          session()->flash('flash.success', 'Registro creado con éxito');
          return redirect()->route($this->name . '.index');
     }

     public function edit($id) {
          $record = $this->model::findOrFail($id);
          return view('admin.'.$this->name . '.edit', compact('record'));
     }

     public function update(Request $request, $id) {
          $object = $this->model::findOrFail($id);
          if (method_exists($object, 'getFieldsFiles')) {
               $object->fill($request->except($object->getFieldsFiles()));
          }else{
               $object->fill($request->all());
          }
          $object->save();
          session()->flash('flash.success', 'Registro actualizado con éxito');
          return redirect()->route($this->name . '.index');
     }

     public function destroy($id) {
          $this->model::findOrFail($id)->delete();
          session()->flash('flash.success', 'El registro se eliminó con éxito');
          return redirect()->route($this->name . '.index');
     }

     private function setValidate($request){
          $validatedData = $request->validate([
               'name' => 'required',
               'color' => 'required'
          ]);
     }

}

