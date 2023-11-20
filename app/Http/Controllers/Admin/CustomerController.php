<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order};
use Illuminate\Http\Request;
use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;
use View;

class CustomerController extends Controller {

    private $model = 'App\Models\User';
    private $name = 'customer';
    private $singular = 'Cliente';
    private $plural = 'Clientes';

    public function __construct() {
        View::share('name', strtolower($this->name));
        View::share('singular', $this->singular);
        View::share('plural', $this->plural);
    }

    public function index() {
        $query = $this->model::orderBy('id', 'DESC');
         $field_form = [];
        if (!empty(request()->get('email'))) {
            $field_form['email'] = request()->get('email');
            $query->where('email', 'LIKE', "%{$field_form['email']}%");
        }

        if (!empty(request()->get('name'))) {
            $field_form['name'] = request()->get('name');
            $query->where('name', 'LIKE', "%{$field_form['name']}%");
        }

        if (!empty(request()->get('document'))) {
            $field_form['document'] = request()->get('document');
            $query->where('document', 'LIKE', "%{$field_form['document']}%");
        }
        $paginate = 25;
        $count = $query->count();
        $records = $query->paginate($paginate); 
        $currentPage = $records->currentPage();       
        if ($currentPage > 1) {
           $count = $count - (($currentPage - 1) * ($paginate));
        }
        return view('admin.' . $this->name . '.index', compact('records', 'field_form', 'count'));
    }

    public function show($id) {
        $user = $this->model::findOrFail($id);
        $orders = Order::where('customer_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();
        return view('admin.' . $this->name . '.show', compact('user', 'orders'));
    }

    public function edit($id) {
        $user = $this->model::findOrFail($id);
        return view('admin.' . $this->name . '.edit', compact('user'));

    }

    public function update(Request $request, $id) {
        $user = $this->model::find($id);
        $rules = [
            'name' => 'required',
            'type_document' => 'required',
            'document' => 'required',
            'type_document' => 'required',
            'phone' => 'numeric',
            'mobile' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];
        $request->validate($rules);

        $user->fill($request->all());
        $user->save();
        session()->flash('flash.success', 'Registro actualizado con éxito');
        return redirect()->route($this->name . '.index');
    }

    public function export(){
      $collection = collect();
      $titles = ['Nombre completo', 'Teléfono', 'Email', 'Tipo de documento', 'Documento', 'Fecha'];
      $collection->push($titles);
      $title_file = 'Usuarios registrados en el e-commerce';
      $query = $this->model::orderBy('id');
      $count = $query->count();
      foreach ($query->get() as $value) {
        $data = [
          $count,
          $value->name . ' ' . $value->lastname,
          $value->phone,
          $value->email,
          $value->type_document,
          $value->document,
          $value->created_at
        ];
        $collection->push($data);
        $count --;
      }      

     return Excel::download(new ExportExcel($collection), $title_file . '.xlsx');     
    }
}