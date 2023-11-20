<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\FormContact;


class FormContactController extends Controller {

     private $model = 'App\Models\FormContact';
     private $name = 'formcontacts';
     private $plural = 'Formulario de contacto';
     /**
          * Display a listing of the resource.
          *
          * @return \Illuminate\Http\Response
          */

     public function __construct() {
          View::share('name', $this->name);
          View::share('plural', $this->plural);
     }

     public function index() {
          $records = $this->model::order();
          return view('admin.'.$this->name . '.index', compact('records'));
     }

     public function destroy($id) {       
          $record = $this->model::findOrFail($id);
          $record->delete();
          session()->flash('flash.success', 'El registro se eliminó con éxito');
          return redirect()->route($this->name . '.index');
     }

     public function export(FormContact $query) {
          $query->limit(10);
          $titles = ['Nombre', 'Correo', 'Celular', 'Ciudad', 'Dirección', 'Asunto', 'Servicio', 'Mensaje'];
          $fields = ['name', 'email', 'mobile', 'city', 'address', 'subject', 'service', 'message'];
          return $this->export_($query, $titles, $fields, 'Formulario de Contactos');
     }

     private function export_($query, $titles, $fields, $filename) {
          $headers = [
          'Content-Encoding' => 'UTF-8',
          'Content-Type' => 'text/csv;charset=UTF-8',
          'Content-Disposition' => "attachment; filename={$filename}.csv",
          ];

          $response = new StreamedResponse(function () use ($query, $headers, $titles, $fields) {
          // Open output stream
          $handle = fopen('php://output', 'w');
          fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
          fputcsv($handle, $titles);
          $query::chunk(100, function ($records) use ($handle, $titles, $fields) {
               foreach ($records as $record) {
                    fputcsv($handle, $this->getRows($record, $fields));
               }

          });
          // Close the output stream
          fclose($handle);
          }, 200, $headers);

          return $response;
     }

     private function getRows($value, $fields) {
          $row = [];
          foreach ($fields as $field) {
          $row[] = $value[$field];
          }
          return $row;
     }    

}

