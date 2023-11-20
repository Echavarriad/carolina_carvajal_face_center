<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class TranslationElementController extends Controller {

    private $singular = 'Elemento';
    private $plural = 'Elementos';

    public function __construct() {
        View::share('singular', $this->singular);
        View::share('plural', $this->plural);
    }

    public function index() {
         $element = request()->get('s');
         $langs = $this->getLangs($element);
         // dd($langs);
        return view('admin.'.'translationelement.index', compact('langs', 'element'));
    }


    public function create() {
        $element = request()->get('s');
        return view('admin.'.'translationelement.create', compact('element'));

    }


    public function store(Request $request) {
        $element = request()->get('s');
        $data = $request->all();
        // dd($data);
        $this->createTranslation($data, $element);
        session()->flash('flash.success', 'Registro creado con éxito');
        return redirect()->route('translationelement.index', ['s' => $element]);

    }
    public function edit($id) {
        $element = request()->get('s');
        $langs = $this->getLangs($element);
        return view('admin.'.'translationelement.edit', compact('id', 'langs', 'element'));
    }



    public function update(Request $request, $id) {
        $element = request()->get('s');
        $data = $request->all();
        $this->updateTranslation($data['es'], 'es', $element);
        session()->flash('flash.success', 'Registro actualizado con éxito');
        return redirect()->route('translationelement.index', ['s' => $element]);
    }



    public function destroy($id) {
        $element = request()->get('s');
        $this->deleteTranslation($id, $element);
        session()->flash('flash.success', 'El registro se eliminó con éxito');
        return redirect()->route('translationelement.index', ['s' => $element]);
    }

    private function createTranslation($data, $element) {
        $langs = $this->getLangs($element);
        $langs['es'][$data['key']] = $data['es'];
        $path_es = resource_path() . '/lang/es/' . $element . '.php';
        $content = "<?php\nreturn " . var_export($langs['es'], true) . ";";
        File::put($path_es, $content);
    }

    private function updateTranslation($value, $lang, $element) {
        $path = resource_path() . '/lang/' . $lang . '/' . $element . '.php';
        $data = require $path;
        foreach($value as $key => $value){
            $field = $key;
        }

        $data[$field] = $value == null ? '' : $value;

        $content = "<?php\nreturn " . var_export($data, true) . ";";

        File::put($path, $content);

    }

    private function deleteTranslation($id, $element) {
        $langs = $this->getLangs($element);
        unset($langs['es'][$id]);
        $path_es = resource_path() . '/lang/es/' . $element . '.php';
        $content = "<?php\nreturn " . var_export($langs['es'], true) . ";";
        File::put($path_es, $content);
    }

   public function getLangs($element) {
        $data['es'] = require resource_path() . '/lang/es/'. $element. '.php';
        return $data;
    }

}

