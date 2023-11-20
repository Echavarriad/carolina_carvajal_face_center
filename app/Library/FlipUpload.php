<?php 



namespace App\Library;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FlipUpload{

    public static function save($file , $folder = '' , $hash = false , $disk = 'uploads'){

     if($hash){
         $fullFileName = Storage::disk($disk)->put($params['folder'], $file);
        }else{           
            $ext = $file->getClientOriginalExtension();
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = Str::slug($fileName . '-');
            $newFilename = $fileName;
            $i = 0;

            while (Storage::disk($disk)->exists($folder.'/'.$newFilename.'.'.$ext)) {
                $newFilename = $fileName . '-' . $i++;
            }
            $fullFileName = Storage::disk($disk)->putFileAs(
                $folder,
                $file,
                $newFilename.'.'.$ext
            );
        }

        return $fullFileName;
    }

}