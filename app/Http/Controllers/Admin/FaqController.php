<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\FaqRepository as Repo;
use App\Http\Requests\FaqRequest as RequestModel; 
use Illuminate\Support\Facades\View;

class FaqController extends Controller {

   private $repository;
   private $model = 'App\Models\Faq';
   private $name = 'faqs';
   private $singular = 'Pregunta';
   private $plural = 'Preguntas frecuentes';

   public function __construct(Repo $repository) {
      $this->repository = $repository;
      View::share('name', strtolower($this->name));
      View::share('singular', $this->singular);
      View::share('plural', $this->plural);
      View::share('section', $this->repository->getSection());
   }

   public function index() {
      $records = $this->repository->all()->get();

      return view('admin.'.$this->name . '.index', compact('records'));
   }

   public function create() {
      $back = route($this->name . '.index', ['foreign' => $this->repository->getSection()]);
      
      return view('admin.'.$this->name . '.create', compact('back'));
   }

   public function store(RequestModel $request) {
      $data = $request->all();
      $data['order'] = $this->repository->order();
      $object = $this->model::create($data);
      $object->save();
      session()->flash('flash.success', 'Registro creado con éxito');

      return redirect($request->_back);
   }

   public function edit($id) {
      $back = route($this->name . '.index', ['foreign' => $this->repository->getSection()]);
      $record = $this->repository->get($id);
      
      return view('admin.'.$this->name . '.edit', compact('record', 'back'));
   }

   public function update(RequestModel $request, $id) {
      $object = $this->repository->get($id);
      $object->fill($request->all());
      $object->save();
      session()->flash('flash.success', 'Registro actualizado con éxito');

      return redirect($request->_back);
   }

   public function destroy($id) {
      $this->repository->delete($id);//Enviar array del name imgs junto al id
      session()->flash('flash.success', 'El registro se eliminó con éxito');

      return redirect()->route($this->name . '.index', ['foreign' => $this->repository->getSection()]);
   }

   public function order(Request $request) {       
      if ($request->ajax()) {
         $this->repository->sort();
         
         return response()->json(['status' => 1]);
      }
   } 
}