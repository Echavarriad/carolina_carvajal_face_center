<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\FaqCategoryRepository as Repo;
use App\Http\Requests\FaqCategoryRequest as RequestModel; 
use Illuminate\Support\Facades\View;

class FaqCategoryController extends Controller {

   private $repository;
   private $model = 'App\Models\FaqCategory';
   private $name = 'faqcategories';
   private $singular = 'Registro';
   private $plural = 'Categorías de las preguntas';

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
      $this->model::create($data);

      session()->flash('flash.success', 'Registro creado con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function edit($id) {
         $record = $this->repository->get($id);

         return view('admin.'.$this->name . '.edit', compact('record'));
   }

   public function update(RequestModel $request, $id) {
      $object = $this->repository->get($id);
      $object->fill($request->all());
      $object->save();
      session()->flash('flash.success', 'Registro actualizado con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function destroy($id) {
      $object = $this->repository->get($id);
      $object->faqs()->delete();
      $object->delete();
      session()->flash('flash.success', 'El registro se eliminó con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function featured(){
      $response = $this->repository->featured(2);

      return response()->json($response);
   } 
}