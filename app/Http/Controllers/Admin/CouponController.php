<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\CouponRepository as Repo;
use App\Http\Requests\CouponRequest as RequestModel; 
use Illuminate\Support\Facades\View;
use App\Models\User;

class CouponController extends Controller {

   private $repository;
   private $model = 'App\Models\Coupon';
   private $name = 'coupons';
   private $singular = 'Cupón';
   private $plural = 'Cupones';

   public function __construct(Repo $repository) {
      $this->repository = $repository;
      View::share('name', strtolower($this->name));
      View::share('singular', $this->singular);
      View::share('plural', $this->plural);
   }

   public function index() {
      $records = $this->repository->all()->paginate();

      return view('admin.'.$this->name . '.index', compact('records'));
   }

   public function create() {
      return view('admin.'.$this->name . '.create');
   }

   public function store(RequestModel $request) {
      $data = $request->all();
      if (!empty($data['_email_customer'])) {
         $user = User::whereEmail($data['_email_customer'])->first();
         $data['customer_id'] = $user->id;
      }
      
      $this->model::create($data);
      session()->flash('flash.success', 'Registro creado con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function edit($id) {
         $record = $this->repository->get($id);
         $user = null;
         if($record->customer_id){
               $user = User::find($record->customer_id);
         }
         return view('admin.'.$this->name . '.edit', compact('record', 'user'));
   }

   public function update(RequestModel $request, $id) {
      $data = $request->all();
      $object = $this->repository->get($id);
      $data['customer_id'] = null;
      if (!empty($data['_email_customer']) && ($object->customer_id != $data['_email_customer'])) {
         $user = User::whereEmail($data['_email_customer'])->first();
         $data['customer_id'] = $user->id;
      }
      $object->fill($data);
      $object->save();
      session()->flash('flash.success', 'Registro actualizado con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function destroy($id) {
      $this->repository->delete($id);
      session()->flash('flash.success', 'El registro se eliminó con éxito');

      return redirect()->route($this->name . '.index');
   }

   public function status(){
      $response = $this->repository->status();

      return response()->json($response);
   }

   public function search_customer(){
      $query = request('query');
      $users = User::where('name' , 'LIKE' , "%$query%")
            ->orWhere('email' , 'LIKE' , "%$query%")
            ->get();

      return response()->json(['status' => true, 'data' => ["users" => $users]]);
  }
}