<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use View;

class ModuleController extends Controller {
    private $model = 'App\Models\Module';
    private $name = 'modules';
    private $singular = 'Módulo';
    private $plural = 'Módulos';

    public function __construct() {
        View::share('name', strtolower($this->name));
        View::share('singular', $this->singular);
        View::share('plural', $this->plural);
    }
    public function index() {
        $records = $this->model::paginate();
        return view('admin.' . $this->name . '.index', compact('records'));
    }

    public function create() {
        return view('admin.' . $this->name . '.create');
    }

    public function store(Request $request) {
       $data = $request->all();  
        $this->setValidate($request);
        $object = $this->model::create($data);     
        $object->save();
        session()->flash('flash.success', 'Registro creado con éxito');
        return redirect()->route($this->name . '.index');
    }

    public function edit($id) {
        $record = $this->model::findOrFail($id);
        $settings = $record->settings;
        // dd($settings['rate']);
        return view('admin.' . $this->name . '.edit', compact('record', 'settings'));
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        if (!isset($data['_values']['test'])) {
          $data['_values']['test'] = 0;
        }else{
          $data['_values']['test'] = 1;
        }
        $module = $this->model::findOrFail($id);
        $module->settings = $data['_values'];
        $module->description = $data['description'];
        $module->save();
        session()->flash('flash.success', 'Registro actualizado con éxito');
        return redirect()->route($this->name . '.index');
    }


    public function status() {
        $data = request()->all();
        $module = $this->model::findOrFail($data['id']);
         if ($data['status'] == 'true') {
            $message = 'Módulo activado.';
            $module->active = 1;
        } else {
            $message = 'Módulo desactivado.';
            $module->active = 0;
        }
        $module->save();

        return response()->json(['status' => true, 'message' => $message]);
    }

    private function setValidate($request){
        $validatedData = $request->validate([
             'name' => 'required',
             'logo' => 'required|image|mimes:jpeg,bmp,png,svg|max:3000'

        ]);
     }

}
