<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use App\Http\Requests\ContentRequest;
use App\Models\Content;
use App\Models\Section;

class ContentController extends Controller {
    /**
     * Display a listing of the resource.
     *

     * @return \Illuminate\Http\Response

     */

    public function index($id) {
        $records = Content::orderBy('order')->get();

        return view('admin.contents.index', compact('records'));
    }



    public function create() {
        $section = Section::findOrFail(request()->get('s'));

        return view('admin.contents.create', compact('section'));
    }

    public function store() {
        $data = request()->all();
        Content::create($data);
        session()->flash('flash.success', 'Registro creado con éxito');

        return redirect()->route('contents.show', $data['section_id']);
    }

    public function show($id_section) {
        $section = Section::with('contents')->findOrFail($id_section);
        $records = $section->contents;

        return view('admin.contents.show', compact('records', 'section'));
    }

    public function edit(Content $content) {
        $id_section = request()->get('s');
        $contentFields = [
            'fields' => [],
            'dimensions' => [],
            'width' => [],
            'height' => [],
            'labels' => [],
        ];
        $fields = config('fieldscontent');
        if ($content->fields()->exists()) {
            $contentFields = (array) json_decode($content->fields->fields);
            $contentFields['dimensions'] = (array) $contentFields['dimensions'];
            $contentFields['width'] = (array) $contentFields['width'];
            $contentFields['height'] = (array) $contentFields['height'];
            $contentFields['labels'] = (array) $contentFields['labels'];
        }

        return view('admin.contents.edit', compact('content', 'id_section', 'contentFields', 'fields'));
    }

    public function update(ContentRequest $request, $id) {
        $content = Content::findOrFail($id);
       if (method_exists($content, 'getFieldsFiles')) {
            $content->fill($request->except($content->getFieldsFiles()));
        }else{
            $content->fill($request->all());
        }

        $content->save();
        session()->flash('flash.success', 'Registro actualizado con éxito');

        return redirect()->route('contents.show', [$content->section_id]);

    }

    public function fields($id_content, $id_section) {
        $content = Content::find($id_content);
        $name_content = $content->name;
        $fields = config('fieldscontent');
        $currentFields = [
            'fields' => [],
            'dimensions' => [],
            'width' => [],
            'height' => [],
            'labels' => [],
        ];
        if ($content->fields()->exists()) {
            $currentFields = (array) json_decode($content->fields->fields);
            $currentFields['dimensions'] = (array) $currentFields['dimensions'];
            $currentFields['width'] = (array) $currentFields['width'];
            $currentFields['height'] = (array) $currentFields['height'];
            $currentFields['labels'] = (array) $currentFields['labels'];
        }
        return view('admin.contents.fields', compact('fields', 'id_content', 'id_section', 'currentFields', 'name_content'));
    }



    public function save_fields() {
        $data = request()->all();
        $fields = [
            'fields' => $data['fields'],
            'dimensions' => $data['dimensions'],
            'width' => $data['width'],
            'height' => $data['height'],
            'labels' => $data['labels'],
        ];
        $content = Content::find($data['content']);
        $content->update(['name' => $data['_name_content']]);
        if ($content->fields()->exists()) {
            $content->fields()->update(['fields' => json_encode($fields)]);
        } else {
            $content->fields()->create(['fields' => json_encode($fields)]);
        }
        session()->flash('flash.success', 'Registro actualizado con éxito');
        return redirect()->route('contents.show', [$data['section']]);
    }

     public function delete_pdf($id) {
        $record = Content::findOrFail($id);
        $file_1 = $record->file_1;
        $file_2 = $record->file_2;
        if(!empty($file_1)) unlink('uploads/' . $file_1);
        if(!empty($file_2)) unlink('uploads/' . $file_2);
        $record->file_1 = null;
        $record->file_2 = null;
        $record->save();
        session()->flash('flash.success', 'El archivo se eliminó con éxito');
        return redirect()->route('contents.show', $record->section_id);

    }

}

