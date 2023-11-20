<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use View;

class TranslationController extends Controller {

    private $name = 'translation';

    public function __construct() {
        View::share('name', strtolower($this->name));
    }

    public function index() {
        $langs = $this->getLangs();
        // dd($langs);
        return view('admin.'.'translation.index', compact('langs'));
    }

    public function create() {
        return view('admin.'.'translation.create');
    }

    public function store(Request $request) {
        $data = $request->all();
        $this->createTranslation($data);
        session()->flash('flash.success', 'Registro creado con éxito');
        return redirect()->route('translation.index');
    }

    public function edit($id) {
        $langs = $this->getLangs();
        return view('admin.'.'translation.edit', compact('id', 'langs'));
    }



    public function update(Request $request, $id) {
        $data = $request->all();
        $this->updateTranslation($data['es'], 'es');
        session()->flash('flash.success', 'Registro actualizado con éxito');
        return redirect()->route('translation.index');
    }



    public function destroy($id) {
        $this->deleteTranslation($id);
        session()->flash('flash.success', 'El registro se eliminó con éxito');
        return redirect()->route('translation.index');
    }

    private function createTranslation($data) {
        $langs = $this->getLangs();
        $langs['es'][$data['key']] = $data['es'];
        $path_es = resource_path() . '/lang/es/generals.php';
        $content = "<?php\nreturn " . var_export($langs['es'], true) . ";";
        File::put($path_es, $content);
    }



    private function updateTranslation($value, $lang) {
        $path = resource_path() . '/lang/' . $lang . '/generals.php';
        $data = require $path;
        foreach ($data as $key => $item) {
            if (isset($value[$key])) {
                $data[$key] = $value[$key];
            }
        }
        $content = "<?php\nreturn " . var_export($data, true) . ";";

        File::put($path, $content);

    }

    private function deleteTranslation($id) {

        $langs = $this->getLangs();
        unset($langs['es'][$id]);
        $path_es = resource_path() . '/lang/es/generals.php';
        $content = "<?php\nreturn " . var_export($langs['es'], true) . ";";
        File::put($path_es, $content);
    }

    public function getLangs() {
        $data['es'] = require resource_path() . '/lang/es/generals.php';
        return $data;
    }
}

