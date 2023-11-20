<?php 

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use File;

class BaseRepository
{

	protected $model;
	private $relations;
  private $foreign_key;
  private $value;

	public function __construct(Model $model, array $relations = [], $foreign_key = null,  $value = null)
	{
		$this->model = $model;
    $this->relations = $relations;
    $this->foreign_key = $foreign_key;
		$this->value = $value;
	}

	public function all(){
		$query = $this->model->order();
    if ($this->foreign_key) {
      $query->where($this->foreign_key, $this->value);
    }
		if (!empty($this->relations)) {
			$query = $query->with($this->relations);
		}
		return $query;
	}

	public function get(int $id){
		return $this->model->findOrFail($id);
	}

	public function order($value = null){
    if ($value) {
      $order = $this->model::where($this->foreign_key, $value)->max('order');
    }else{
      $order = $this->model::max('order');
    } 
    $order = empty($order) ? 1 :  ($order+=1);           
    return $order;
	}

	public function slug(Model $model, $title){
		if (empty($model->slug)) {
     	$model->slug = Str::slug($title, '-') . '-' . $model->id;
    }else{ 
    	$model->slug = Str::slug($model->slug, '-');
    }

    if(isset($model->meta_title) && empty($model->meta_title)) {
      $model->meta_title = $title;
    }
    
    return $model;
	}

	public function delete(int $id, $images = ['image']){
    $object = $this->get($id);
    foreach($images as $field){
      if(isset($object[$field])){
        if(!empty($object[$field]) && Storage::disk('uploads')->exists($object[$field])){
          unlink('uploads/' . $object[$field]);
        }
      }
    }    

		$object->delete();
	}

  public function _featured(int $id) {
      $object = $this->get($id);
      $message = '';
      if ($object->featured == 1) {
          $message = 'El registro dejó de estar destacado.';
          $object->featured = 0;
      } else {
          $message = 'Registro destacado';
          $object->featured = 1;
      }
      $object->save();
      return $message;      
  }

  //ordenar los registros
  public function sort() { 
   	$data = request()->get('data_images');            
   	$updateDefault = request()->get('order');
   	$arrUpdate = array();
   	foreach ($data as $key => $item) {
      $dataUp = ['order' => ($key + 1)];
      $this->model::where('id', $item)->update($dataUp);
   	}
  } 

  public function published() {
    $data = request()->all();
    $object = $this->get($data['id']);
    $message = '';
    if ($data['status'] == 'true') {
        $message = 'Registro publicado.';
        $object->published = 1;
        $type = 'success';
    } else {
        $message = 'Registro despublicado.';
        $object->published = 0;
        $type = 'warning';
    }
    $response = ['status' => true , 'message'=> $message, 'type' => $type];
    $object->save();
    return $response;
  }

  public function featured($limit = 0) {
    $data = request()->all();
    $object = $this->get($data['id']);
    $response['status'] = true;
    $message = '';
    if ($data['status'] == 'true') {
      if($limit > 0 && ($this->model::where('featured', 1)->count() + 1) > $limit){
        $message = 'Solo se permiten ('. $limit . ') registro(s) destacado(s).';
        $object->featured = 0;
        $type= "error";
      }else{
        $message = 'Registro destacado.';
        $object->featured = 1;
        $type = 'success';
      }      
    } else {
      $message = 'El registro dejó de estar destacado.';
      $object->featured = 0;
      $type = 'warning';
    }
    $response['message']= $message;
    $response['type']= $type;
    $object->save();
    return $response;
}

  // Crear las imágenes de la galería de un modelo cualquiera vuejs
  public function createImageGallery($folder) {
      request()->validate([
          'file' => 'required|image|mimes:jpeg,jpg,bmp,png|max:2200',
      ]);
      $last_order = $this->model->max('order');
      if (!$last_order) {
        $last_order = 0;
      }
      $last_order++;
      $this->model->create([
          'image' => \FlipUpload::save(request()->file, $folder),
          'order' => $last_order,
      ]);        
      $response = ['status' => 1];
      return $response;
  }

  public function createImageGalleryRelational($folder) {
      
      request()->validate([
          'file' => 'required|image|mimes:jpeg,jpg,bmp,png,svg|max:2200',
      ]);
      $id = request()->get('model_id');
      $object = $this->get($id);
      $last_order = $object->gallery()->max('order');
      if (!$last_order) {
        $last_order = 0;
      }
      $last_order++;
      $object->gallery()->create([
          'image' => \FlipUpload::save(request()->file, $folder),
          'order' => $last_order,
      ]);        
      $response = ['status' => 1];
      return $response;
  }

  public function sortImageGallery($model) {
      $data = request()->all();               
      $arrUpdate = array();
      $order = 1;
      foreach ($data as $key => $item) {
        if ($item['id']) {
          $dataUp = ['order' => $order];
          $model->where('id', $item['id'])->update($dataUp);
          $order ++;
        } 
      }
      $response = ['status' => 1];
      return $response;
  }

  public function deleteImageGallery($model, $id) {
      $image = $model->find($id);
      File::delete('uploads/' . $image->image);
      $image->delete();
      $response = ['status' => true, 'message' => 'Imagen eliminada exitósamente'];
      return $response;
  }

  public function updateImageGallery($model) {
    $data = request()->all();
    $image = $model->find($data['id']);
    $image->update($data);
    $response = ['status' => true, 'message' => 'Información actualizada exitósamente'];
    return $response;
}

}