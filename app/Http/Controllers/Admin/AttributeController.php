<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Repositories\AttributeRepository as Repo;
use App\Http\Requests\AttributeRequest as RequestModel; 
use Illuminate\Support\Facades\View;
use App\Models\AttributeOption;

class AttributeController extends Controller {

   private $repository;
   private $model = 'App\Models\Attribute';
   private $name = 'attributes';
   private $singular = 'Atributo';
   private $plural = 'Atributos';

   public function __construct(Repo $repository) {
      $this->repository = $repository;
      View::share('name', strtolower($this->name));
      View::share('singular', $this->singular);
      View::share('plural', $this->plural);
   }

   public function index() {
      $records = $this->repository->all()->get();

      return view('admin.'.$this->name . '.index', compact('records'));
   }

   public function create() {
      return view('admin.'.$this->name . '.create');
   }

   public function store(RequestModel $request) {
      $data = $request->all();
      $data['order'] = $this->repository->order();
      $object = $this->model::create($data);
      $object = $this->repository->slug($object, $object->title);
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
      $object = $this->repository->slug($object, $object->title);
      $object->save();
      session()->flash('flash.success', 'Registro actualizado con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function destroy($id) {
      $object = $this->repository->get($id);
      $object->options()->delete();
      $object->delete();

      session()->flash('flash.success', 'El registro se eliminó con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function order(Request $request) {       
      if ($request->ajax()) {
         $this->repository->sort();
         
         return response()->json(['status' => 1]);
      }
   } 

   //Opciones de los atributes
   public function options($id){
      $attribute = $this->model::with('options')->find($id);
      
      return view('admin.' . $this->name . '.options', compact('attribute'));
   }

   public function master_option(){
      $data = request()->all();
      if (!$data['id']) {
         $order = AttributeOption::where('attribute_id', $data['attribute_id'])->max('order');
         if (empty($order)) {
            $order = 1;
         } else {
            $order++;
         }
         $data['order'] = $order;
         $data['slug'] = Str::slug($data['name'], '-');
         AttributeOption::create($data);
         $message =  'Opción guardada exitósamente.';
      }else{
         $object = AttributeOption::find($data['id']);
         $data['slug'] = Str::slug($data['name'], '-');
         $object->fill($data);
         $object->save();
         $message =  'Opción actualizada exitósamente.';
      }
      return response()->json(['status' => true , 'message'=> $message]);
   }

   public function delete_option($id){
      AttributeOption::find($id)->delete();

      return response()->json(['status' => true , 'message'=> 'Opcíon eliminada exitósamente.']);
   }

   public function order_options(){
      $data = request()->all(); 
      $order = 1;
      foreach ($data as $item) {
         if ($item['id']) {
         $dataUp = ['order' => $order];
         AttributeOption::where('id', $item['id'])->update($dataUp);
         $order ++;
         } 
      }

      return response()->json(['status' => 1]);
   }
}