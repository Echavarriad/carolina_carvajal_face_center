<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SliderRepository as Repo;
use App\Http\Requests\SliderRequest as RequestModel;
use Illuminate\Support\Facades\View;

class SliderController extends Controller {

   private $repository;
   private $model = 'App\Models\Slider';
   private $name = 'sliders';
   private $singular = 'Slider';
   private $plural = 'Sliders';

   public function __construct(Repo $repository) {
      $this->repository = $repository;
      View::share('name', strtolower($this->name));
      View::share('singular', $this->singular);
      View::share('plural', $this->plural);
   }

   public function index() {
      /* $records = $this->repository->all()->get(); */

      return view('admin.'.$this->name . '.index'/* , compact('records') */);
   }

   public function create() {
      return view('admin.'.$this->name . '.create');
   }

   public function store(RequestModel $request) {
      $data = $request->all();
      $data['order'] = $this->repository->order();
      $object = $this->model::create($data);
      $object->save();
      session()->flash('flash.success', 'Registro creado con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function edit($id) {
         $record = $this->repository->get($id);

         return view('admin.'.$this->name . '.edit', compact('record'));
   }

   public function update(RequestModel $request, $id) {
      $object = $this->repository->get($id);
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
      $this->repository->delete($id);
      session()->flash('flash.success', 'El registro se eliminó con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function order(Request $request) {
      if ($request->ajax()) {
         $this->repository->sort();

         return response()->json(['status' => 1]);
      }
   }
}
