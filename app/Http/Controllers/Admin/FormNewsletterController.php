<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FormNewsletterController extends Controller {

    private $model = 'App\Models\FormNewsletter';
    private $name = 'formnewsletter';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct() {
        View::share('name', $this->name);
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

    public function export() {
        $name_file = 'Suscriptores al boletín.csv';
        $headers = array(
            "Content-Encoding" =>  "UTF-8",
            "Content-type" => "text/csv ; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=" . $name_file,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
            );

        $records = $this->model::order();
        $columns = array('EMAIL');

        $callback = function() use ($records, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($records as $record) {
                fputcsv($file, array($record->email));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
    
    
}

