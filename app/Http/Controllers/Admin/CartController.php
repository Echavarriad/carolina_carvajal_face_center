<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;

class CartController extends Controller {

    private $model = 'App\Models\Cart';
    private $name = 'cart';
    private $singular = 'Carrito';
    private $plural = 'Carritos abandonados';

     public function __construct() {
          View::share('name', strtolower($this->name));
          View::share('singular', $this->singular);
          View::share('plural', $this->plural);
     }

     public function index() {
        $records = [];
        $field_form = [];
        $query = $this->model::orderBy('carts.created_at', 'DESC');
        if (request()->get('date_initial')) {
          $field_form['date_initial'] = request()->get('date_initial');
          $field_form['date_final'] = request()->get('date_final');
          $query->whereBetween('carts.created_at', [$field_form['date_initial'] . ' 00:00:00',$field_form['date_final']. ' 23:59:59']);
        } 
        $paginate = 25;
        $count = $query->count();
        $query = $query->paginate($paginate); 
        $currentPage = $query->currentPage();       
        if ($currentPage > 1) {
           $count = $count - (($currentPage - 1) * ($paginate));
        }
        foreach ($query as $key => $value) {
          $records[] = $value->formatted();
        }
        return view('admin.'.$this->name . '.index', compact('records', 'query', 'field_form', 'count'));
     }

    public function show($id) {
      $cart = $this->model::with(['address'])->find($id);
      $cart = $cart->formatted();
      return view('admin.'.$this->name . '.show', compact('cart'));
    }

    public function export(){
      $collection = collect();
      $titles = ['Id', 'Tipo de compra', 'Nombre', 'Teléfono', 'Correo', 'Fecha'];
      $collection->push($titles);
      $title_file = 'Carritos abandonados';
      $query = $this->model::orderBy('created_at', 'DESC'); 
      if (request()->get('date_initial')) {
        $field_form['date_initial'] = request()->get('date_initial');
        if (request()->get('date_final')) {
          $field_form['date_final'] = request()->get('date_final');
        }else{
          $field_form['date_final'] = date('Y-m-d');
        }
        $query->whereBetween('created_at', [$field_form['date_initial'] . ' 00:00:00',$field_form['date_final']. ' 23:59:59']);
      }

      if (request()->get('date_final')) {
          $field_form['date_final'] = request()->get('date_final');
        if (request()->get('date_initial')) {
          $field_form['date_initial'] = request()->get('date_initial');
        }else{
          $field_form['date_initial'] = '2021-07-01';
        } 
        $query->whereBetween('created_at', [$field_form['date_initial'] . ' 00:00:00',$field_form['date_final']. ' 23:59:59']);
      }
       $count = $query->count();
      foreach ($query->get() as $key => $value) {
        $data = [
          $count,
          $value->type_cart == 'product' ? 'Productos' : 'Habitaciones',
          $value->customer_name,
          $value->customer_phone,
          $value->customer_email,
          $value->created_at
        ];
        $collection->push($data);
         $count --;
      }      

     return Excel::download(new ExportExcel($collection), $title_file . '.xlsx');     
    }    
}

