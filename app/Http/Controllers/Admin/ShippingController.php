<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ShippingRepository as Repo;
use App\Http\Requests\ShippingRequest as RequestModel;
use App\Models\City;
use Illuminate\Support\Facades\View;
use App\Models\State;

class ShippingController extends Controller {

   private $repository;
   private $model = 'App\Models\Shipping';
   private $name = 'shippings';
   private $singular = 'Registro';
   private $plural = 'Tabla de fletes';

   public function __construct(Repo $repository) {
      $this->repository = $repository;
      View::share('name', strtolower($this->name));
      View::share('singular', $this->singular);
      View::share('plural', $this->plural);
      View::share('states', State::with('cities')->get());
   }

   public function index(Request $request) {
      $city= $request->city;
      if($city){
         $records = $this->model::where('name_city', 'LIKE', '%'. $city . '%')->paginate();
      }else{
         $records = $this->repository->all()->paginate(40);
      }

      return view('admin.'.$this->name . '.index', compact('records', 'city'));
   }

   public function create() {
      return view('admin.'.$this->name . '.create');
   }

   public function store(RequestModel $request) {
      $data = $request->all();
      $data['code_dane']= $data['code_state'] . $data['code_city'];
      $object = $this->model::create($data);
      $object->save();
      session()->flash('flash.success', 'Registro creado con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function edit($id) {
         $record = $this->repository->get($id);
         $state= State::with('cities')->find($record->code_state);

         return view('admin.'.$this->name . '.edit', compact('record', 'state'));
   }

   public function update(RequestModel $request, $id) {
      $object = $this->repository->get($id);
      $object->fill($request->all());
      $object->save();
      session()->flash('flash.success', 'Registro actualizado con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function destroy($id) {
      $this->repository->delete($id);//Enviar array del name imgs junto al id
      session()->flash('flash.success', 'El registro se eliminó con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function isActiveShippingFee(){
      $response = $this->repository->active();

      return response()->json($response);
   } 
}