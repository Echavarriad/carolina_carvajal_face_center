<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Library\ResizeImage;
use Illuminate\Support\Str;

class UploadsImageController extends Controller
{

    // Imágenes del Producto
    public function uploads(Request $request){
        $image= $request->image;
        $image_before= $request->image_before;
        $id= $request->id;
        $width= intval($request->with);
        $height= intval($request->height);
        $folder= $request->folder;
        $field= $request->field;
        $model= 'App\Models\\'.$request->model;
        $path = 'uploads/' . $folder . '/';
        if(!is_dir($path)){ //Si no existe el directorio se crea uno
            @mkdir($path, 0777); 
        }
        // if(in_array($request->model, ['Product', 'ProductGallery', 'Variation'])){
        if(1==1){
            $resp= $this->saveImageOriginal($image, $path);
        }else{
            $resp= $this->resize_image($image, $width, $height, $path);
            if(!$resp){
                return response()->json(['status' => false, 'message' => 'El tipo de archivo no es válido, solo se permiten imágenes (.jpg, .png, .gif, .jpeg, .JPG']);
            }
        }
        
        $name_image = $folder . '/' . $resp;
        
        $model::find($id)->update([
            $field => $name_image
        ]);
        

        if(!empty($image_before) && $image_before != 'null' && Storage::disk('uploads')->exists($image_before)){
            unlink('uploads/' . $image_before);
        }
        
        $response = ['status' => true, 'message' => 'Imagen actualizada con éxito', 'name_image' => $name_image];
    
        return response()->json($response);
    }

    

    private function saveImageOriginal($image, $path){        
        $extension = $image->getClientOriginalExtension();
        $fileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . ' ' . time();
        $fileName = Str::slug($fileName . '-') . '.' . $extension;
        $path= public_path($path) . $fileName;
        copy($image->getRealPath(),$path);

        return $fileName;
    }

    public function delete_image(Request $request){
        $image= $request->image;
        $id= $request->id;
        $field= $request->field;
        $deleteRecord= $request->delrecord;
        $model= 'App\Models\\'.$request->model;
        if(!$deleteRecord){
            $model::find($id)->update([
                $field => NULL
            ]);
        }else{
            $model::find($id)->delete();
        }
        
        if(!empty($image) && Storage::disk('uploads')->exists($image)){
            unlink('uploads/' . $image);
        }

        return response()->json(['status' => true, 'message' => 'Imagen eliminada exitósamente']);
    }

    // ResizeImages image
    private function resize_image($file, $width, $height, $path){ 
        $ext = '.webp';
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME). '-' . time();
        $pathTo= public_path($path);
        $resizeObj = new ResizeImage($file);
        if(!$resizeObj->getImage()){
            return false;
        }
        if($width == 0 && $height == 0){
            $width= $resizeObj->getWidth();
            $height= $resizeObj->getHeight();
        }
        $option = $resizeObj->getSizes($width, $height);
        //Resize image (options: exact, portrait, landscape, auto, crop)
        $resizeObj->resizeImage($width, $height, $option);
  
        // *** 3) Save image
        $resized_image = $resizeObj->getResizedImage();
        $exif = @exif_read_data($file);
        if ($exif && isset($exif['Orientation'])) {
            switch ($exif['Orientation']) {
            case 3:
                $resized_image = imagerotate($resized_image, 180, 0);
                break;
            case 6:
                $resized_image = imagerotate($resized_image, -90, 0);
                break;
            case 8:
                $resized_image = imagerotate($resized_image, 90, 0);
                break;
            }
        }
        $fileName =  Str::slug($fileName . '-');
        if($resizeObj->getExt() == 'png'){
            imagepng($resized_image, $pathTo . $fileName . $ext);
        }else{
            imagejpeg($resized_image, $pathTo . $fileName . $ext);
        }        

        imagedestroy($resized_image);

        return $fileName.$ext;
    }
}
