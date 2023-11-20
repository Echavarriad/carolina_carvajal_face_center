<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class AjaxController extends Controller {

    public function cities() {
        $id = request()->get('id');
        $cities = City::where('country_id', $id)->get();
        return response()->json($cities);
    }

    public function order() {
        $data = request()->all();
        // dd($data);
        // exit('tambien llego aca llego aca');
        $model = 'App\Models\\'.$data['model'];
        foreach ($data['data_images'] as $key => $item) {
            $dataUp = ['order' => ($key + 1)];
            $model::where('id', $item)->update($dataUp);
        }
        return response()->json(['status' => 1]);
    }

    public function delete_file() {
        $data = request()->all();
        $response = ['status' => true, 'message' => 'Archivo eliminado exitósamente'];
        $model = 'App\Models\\'.$data['model'];
        $record= $model::find($data['id']);
        // dd($record[$data['field']]);
        unlink('uploads/' . $record[$data['field']]);
        $record->update([$data['field'] => '']);
        return response()->json($response);
    }
}
