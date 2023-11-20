<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository as Repo;
use App\Http\Requests\CategoryRequest as RequestModel;
use Illuminate\Support\Facades\View;
use App\Models\{ProductCategory};

class CategoryController extends Controller {

    private $repository;
    private $model = 'App\Models\Category';
    private $name = 'categories';
    private $singular = 'Categoría';
    private $plural = 'Categorías';

    public function __construct(Repo $repository) {
        $this->repository = $repository;
        View::share('name', strtolower($this->name));
        View::share('singular', $this->singular);
        View::share('plural', $this->plural);
        
    }
    public function index() {
        $tree = $this->model::defaultOrder()->get(['id', 'name', 'parent_id', '_rgt', '_lft'])->toTree();
        $categories['categories'] = json_encode($tree->toArray());

        return view('admin.' . $this->name . '.index', compact('categories'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.' . $this->name . '.create');
    }

    public function store(RequestModel $request) {
        $data = $request->all();
        $object = $this->model::create($data);
        $object = $this->repository->slug($object, $object->name);
        $object->save();
        session()->flash('flash.success', 'Registro creado con éxito');

        return redirect()->route($this->name . '.index');
     }

    public function edit($id) {
        $record = $this->repository->get($id);
        return view('admin.'.$this->name . '.edit', compact('record'));
     }

    public function update(RequestModel $request, $id) {
        $object = $this->repository->get($id);
        if (method_exists($object, 'getFieldsFiles')) {
             $object->fill($request->except($object->getFieldsFiles()));
        }else{
             $object->fill($request->all());
        } 
        $object = $this->repository->slug($object, $object->name);
        $object->save();
        session()->flash('flash.success', 'Registro actualizado con éxito');

        return redirect()->route($this->name . '.index');
    }

    public function featured($id) {
        $record = $this->repository->get($id);

        return view('admin.' . $this->name . '.featured', compact('record'));
    }

    public function manage() {
        $data = request()->all();
        if ($data['type'] == 'edit') {
            return redirect()->route($this->name . '.edit', $data['id']);
        }

        if ($data['type'] == 'del') {
            ProductCategory::where('category_id', $data['id'])->delete();
            $this->repository->delete($data['id']);
            session()->flash('flash.success', 'El registro se eliminó con éxito');
            
            return redirect()->route($this->name . '.index');
        }
    }

    public function update_position() {
        $data = request()->all();
        if ($data['position'] == 'after') {
            $target = $this->model::find($data['target']);
            $item = $this->model::find($data['id']);
            $item->afterNode($target)->save();
        }
        if ($data['position'] == 'inside') {
            $target = $this->model::find($data['target']);
            $item = $this->model::find($data['id']);
            $target->appendNode($item);
        }
    }

}
