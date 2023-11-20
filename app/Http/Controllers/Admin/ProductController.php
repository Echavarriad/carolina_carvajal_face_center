<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository as Repo;
use App\Http\Requests\ProductRequest as RequestModel;
use App\Http\Resources\RelatedResource;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;
use App\Models\{Category, ProductCategory, ProductGallery, ProductRelated, Variation};

class ProductController extends Controller {

   private $repository;
   private $model = 'App\Models\Product';
   private $name = 'products';
   private $singular = 'Producto';
   private $plural = 'Productos';

   public function __construct(Repo $repository) {
      $this->repository = $repository;
      View::share('name', strtolower($this->name));
      View::share('singular', $this->singular);
      View::share('plural', $this->plural);
   }

   public function index() {
      $response = $this->repository->search();
      $records = $response['query']->paginate(25);
      $field_form = $response['field_form'];

      return view('admin.'.$this->name . '.index', compact('records', 'field_form'));
   }

   public function create() {
      return view('admin.'.$this->name . '.create');
   }

   public function store(RequestModel $request) {
      $data = $request->all();
      $data['order'] = $this->repository->order();
      $object = $this->model::create($data);
      $object = $this->repository->slug($object, $object->name);
      $object->save();

      session()->flash('flash.success', 'Registro creado con éxito, complete la información');

      return redirect()->route($this->name . '.edit', [$object->id]);
   }

   public function edit($id) {
      $back = route($this->name . '.index');
      $record = $this->model::with(['variations', 'relateds', 'gallery'])->find($id);
      //dd($record->variations->length);
      $tree = Category::defaultOrder()->get()->toTree();
      $categories = $tree->toArray();
      $product_cat = $record->categories->pluck('id')->toArray(); 
      $count_related = $record->relateds()->count();

      return view('admin.'.$this->name . '.edit', compact('back', 'record', 'categories', 'product_cat', 'count_related'));
   }

   public function update(RequestModel $request, $id) {
      $data = $request->all();
      if (!isset($data['_categories'])) {
         session()->flash('flash.error', 'Debe seleccionar al menos una categoría');
         return redirect()->route($this->name. '.edit', [$id]);
      }
      $object = $this->model::with('variations')->find($id);
      if (method_exists($object, 'getFieldsFiles')) {
         $object->fill($request->except($object->getFieldsFiles()));
      }else{
         $object->fill($request->all());
      }
      $object = $this->repository->slug($object, $object->name);
      $object->save();
      $categories = [];
      if (isset($data['_categories'])) {
         $categories = $data['_categories'];
      }
      $object->categories()->sync($categories);
      Event::dispatch('product.updated', $object);
      if ($data['_save'] == 'continue') {
         session()->flash('flash.success', 'Información guardada');
         return redirect()->route($this->name . '.edit' , [$object->id]);
      }

      session()->flash('flash.success', 'Registro actualizado con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function destroy($id) {
      $object = $this->model::with(['relateds', 'variations'])->find($id);
      ProductCategory::where('product_id', $id)->delete();
      if(!empty($object->image) && Storage::disk('uploads')->exists($object->image)){
         unlink('uploads/' . $object->image);
      }
      foreach($object->variations as $item){
         if(!empty($item->image) && Storage::disk('uploads')->exists($item->image)){
            unlink('uploads/' . $item->image);
         }
      }
      $object->variations()->delete();
      $object->relateds()->delete();
      $object->delete();

      session()->flash('flash.success', 'El registro se eliminó con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function published(){
      $response = $this->repository->published();

      return response()->json($response);
   }

   public function featured(){
      $response = $this->repository->featured(8);

      return response()->json($response);
   }

   public function order(Request $request) {       
      if ($request->ajax()) {
         $this->repository->sort();
         
         return response()->json(['status' => 1]);
      }
   } 

  // Productos relacionados
   public function get_relateds($id) {
      $product = $this->model::with('relateds.product')->find($id);
      
      return RelatedResource::collection($product->relateds);
   }

   public function search_product() {
      $term = request()->get('term');
      $product = request()->get('product');
      $products = $this->model::where('name', 'LIKE', "%$term%")
         ->where('id', '!=', $product)
         ->limit(15)
         ->get();

      return response()->json($products);
   }

   public function add_related() {
      $data = [
         'product_id' => request()->get('product'),
         'product_related_id' => request()->get('related'),
      ];
     
      $valid = ProductRelated::where('product_id', $data['product_id'])
         ->where('product_related_id', $data['product_related_id'])
         ->exists();
      $response = ['status' => false, 'message' => 'Ya el producto está relacionado'];
      if (!$valid) {
         $related = ProductRelated::create($data);
                  
         return RelatedResource::make($related);
      }

      return response()->json($response);
   }

   public function delete_related($id) {
      ProductRelated::findOrFail($id)->delete();
      return response()->json(['status' => 1]);
   }

   public function order_products_relateds(){
      $data = request()->all();
         foreach ($data as $key => $item) {
         if ($item['id']) {
            $dataUp = ['order' => $key+1];
            ProductRelated::where('id', $item['id'])->update($dataUp);
         } 
         }
         return response()->json(['status' => 1]);
   }

   public function addGallleryToProduct(){
      $maxOrder= ProductGallery::where('product_id', request()->product)->max('order');
      $maxOrder = $maxOrder > 0 ? ($maxOrder++) : 1;
      ProductGallery::create(['product_id' => request()->product, 'order' => $maxOrder]);

      $gallery= ProductGallery::order()->where('product_id', request()->product)->get();

      return response()->json(['status' => true, 'gallery' => $gallery]);
   }

   public function order_products_gallery(){
      $data = request()->all();
      foreach ($data['gallery'] as $key => $item) {
         if ($item['id']) {
            $dataUp = ['order' => $key+1];
            ProductGallery::where('id', $item['id'])->update($dataUp);
         } 
      }
      $gallery= ProductGallery::order()->where('product_id', request()->product)->get();

      return response()->json(['status' => true, 'gallery' => $gallery]);
   }
   
}