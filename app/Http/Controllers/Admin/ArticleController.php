<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ArticleRepository as Repo;
use App\Http\Requests\ArticleRequest as RequestModel; 
use Illuminate\Support\Facades\View;
use App\Models\ArticleGallery;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller {

   private $repository;
   private $model = 'App\Models\Article';
   private $name = 'articles';
   private $singular = 'Registro';
   private $plural = 'Registros';

   public function __construct(Repo $repository) {
      $this->repository = $repository;
      View::share('name', strtolower($this->name));
      View::share('singular', $this->singular);
      View::share('plural', $this->plural);
   }

   public function index() {
      $records = $this->repository->all()->with('gallery')->get();

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
      $object->fill($request->all());
      $object = $this->repository->slug($object, $object->title);
      $object->save();
      session()->flash('flash.success', 'Registro actualizado con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function destroy($id) {
      $this->repository->delete($id);//Enviar array del name imgs junto al id
      session()->flash('flash.success', 'El registro se eliminó con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function published(){
      $response = $this->repository->published();

      return response()->json($response);
   }

   public function featured(){
      $response = $this->repository->featured(2);

      return response()->json($response);
   }

   public function order(Request $request) {       
      if ($request->ajax()) {
         $this->repository->sort();
         
         return response()->json(['status' => 1]);
      }
   } 

     //Galeria

     public function gallery($id){
      $record= $this->model::with('gallery')->find($id);
      session()->put('article_id', $id);

      return view('admin.' . $this->name . '.gallery', compact('record'));
   }

   public function add_image($id){
      $data['article_id'] = $id;
      $order = ArticleGallery::where('article_id', $id)->max('order');
      $data['order']= empty($order) ? 1 :  ($order+=1);
      ArticleGallery::create($data);

      return redirect()->route($this->name . '.images', $id);
   }

   public function delete_image($id_key, $id){
      $object = ArticleGallery::find($id);
      if(!empty($object->image) && Storage::disk('uploads')->exists($object->image)){
         unlink('uploads/' . $object->image);
      }
      $object->delete();
      session()->flash('flash.success', 'El registro se eliminó con éxito');

      return redirect()->route($this->name . '.images', $id_key);
   }

   public function order_gallery(Request $request) {
      if(session()->has('article_id')){
         $id = session()->get('article_id');
      }    
      
      if ($request->ajax()) {
         $data = request()->get('data_images');
         foreach ($data as $key => $item) {
            $dataUp = ['order' => ($key + 1)];
            ArticleGallery::where('article_id', $id)->where('id', $item)->update($dataUp);
         }
         return response()->json(['status' => 1]);
      }
   }
}