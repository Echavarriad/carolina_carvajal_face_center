<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Events\OrderPaid;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Request;
use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\{Status, Payment, Order, OrderItem};
use View;

class OrderController extends Controller {

    private $model = 'App\Models\Order';
    private $name = 'order';
    private $singular = 'Orden';
    private $plural = 'Órdenes de compra';

     public function __construct() {
          View::share('name', strtolower($this->name));
          View::share('singular', $this->singular);
          View::share('plural', $this->plural);
     }

     public function index() {
          $query = $this->model::orderBy('orders.created_at', 'DESC');          
          $field_form = [];
          if (request()->get('reference')) {
              $field_form['reference'] = request()->get('reference');
              $query->where('reference', 'LIKE', "%{$field_form['reference']}%");
          }
          if (request()->get('status')) {
              $field_form['status'] = request()->get('status');
              $query->where('status_id', 'LIKE', "%{$field_form['status']}%");
          }
         if (request()->get('date_initial')) {
            $field_form['date_initial'] = request()->get('date_initial');
            if (request()->get('date_final')) {
              $field_form['date_final'] = request()->get('date_final');
            }else{
              $field_form['date_final'] = date('Y-m-d');
            }
            $query->whereBetween('orders.created_at', [$field_form['date_initial'] . ' 00:00:00',$field_form['date_final']. ' 23:59:59']);
          }

          if (request()->get('date_final')) {
              $field_form['date_final'] = request()->get('date_final');
            if (request()->get('date_initial')) {
              $field_form['date_initial'] = request()->get('date_initial');
            }else{
              $field_form['date_initial'] = '2021-07-01';
            } 
            $query->whereBetween('orders.created_at', [$field_form['date_initial'] . ' 00:00:00',$field_form['date_final']. ' 23:59:59']);
          }
          $statuses = Status::where('type', 'shop')->get();
          $query->select('*','orders.created_at as order_date');
          $paginate = 25;
          $count = $query->count();
          $orders = $query->paginate($paginate);
          $currentPage = $orders->currentPage();       
          if ($currentPage > 1) {
             $count = $count - (($currentPage - 1) * ($paginate));
          }
          return view('admin.'.$this->name . '.index', compact('orders', 'statuses', 'field_form', 'count'));
     }

     public function show($id) {
          $order = Order::with(['status', 'items', 'address', 'user', 'payment'])->find($id);
          $payment = $order->payment;
          $statuses = $this->getStatuses($order->status_id, $order->type_cart);

          return view('admin.'.$this->name . '.show', compact('order', 'payment', 'statuses'));
     }

     public function change_status(Request $request) {
          $data = $request->all();
          $object = $this->model::findOrFail($data['order_id']);
          if ($data['status_id'] == 2) { 
            $object->status_id = 2;  
            $object->save();
            Event::dispatch('order.paid', [$object]);
          }elseif ($data['status_id'] == 5 || $data['status_id'] == 4) { 
            $object->status_id = $data['status_id'];  
            $object->save();
            Event::dispatch('order.declined', [$object]);
          }elseif($data['status_id'] == 3) { 
            $object->status_id = 3;  
            $object->save();

            Event::dispatch('order.send', [$object]);
          }else{
            $object->status_id = 6;  
            $object->save();
          }

          return response()->json(['status' => true]);
     }

     public function download($id){
          $order = Order::with(['status', 'items', 'address_shipping', 'user'])->find($id);
          $order->order_date = $order->created_at;
          $pdf = \PDF::loadView('admin.'.$this->name . '.pdf', compact('order'));
          return $pdf->download('Orden de compra -' . $order->reference . '.pdf');
     }

    private function getStatuses($status_id, $type_cart){
        switch ($status_id) {
          case 1:
            $array_ids = [1, 2, 4];
            break;
          case 2:
            $array_ids = [2, 3];
            break;
          case 3:
            $array_ids = [3, 6];
            break;
          default:
            $array_ids = [$status_id];
            break;
        }      

      return Status::whereIn('id', $array_ids)->get();
    }

    public function export(){
      $collection = collect();
      $titles = ['Ítem', 'Orden de compra', 'Estado', 'Nombre', 'Teléfono', 'Correo', 'Fecha'];
      $collection->push($titles);
      $title_file = 'Órdenes de compra';
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

      if (request()->get('status')) {
          $field_form['status'] = request()->get('status');
          $query->where('status_id', $field_form['status']);
      }
      $count = $query->count();
      foreach ($query->get() as $key => $value) {
        $messageTx = !empty($value->message_status) ? $value->message_status : $value->status->name;
        $data = [
          $count,
          $value->reference,
          $messageTx,
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

    public function update_guide(Request $request) {
      $data = $request->all();
      $object = $this->model::findOrFail($data['order_id']);
      $object->guide_number = $data['guide'];         
      $object->save();
      return response()->json(['status' => true]);
   }

   public function update_url_guide(Request $request) {
      $data = $request->all();
      $object = $this->model::findOrFail($data['order_id']);
      $object->url_guide = $data['url_guide'];         
      $object->save();
      return response()->json(['status' => true]);
   }

}

