<?php

namespace App\Shop\Helpers;

use Illuminate\Support\Facades\Storage;

class FilesProduct
{
  protected $url_img = 'http://www.papyser.com/catalogo/';
  protected $url_file= 'http://www.papyser.com/catalogo/docs/';
  protected $url_default= 'http://www.papyser.com/catalogo/nodisponible.jpg';

	public function image_main($codigo){
    $image_main = $this->getFile($codigo);
 	 	return $image_main;
	}

  public function gallery($codigo){
      $array_images[] = $this->image_main($codigo);
      for ($i=1; $i < 10; $i++) {
        $codigo = $codigo . '-' . $i;
        $file = $this->getFile($codigo);
        if ($this->url_default === $file) {
          break;
        }
        $array_images [] = $file;
      }
      return $array_images;
  }

	public function file($codigo){
    $file = $this->getFile($codigo, 'file', '.pdf');
   	return $file;
	}

  private function getFile($codigo, $type = 'img', $ext = '.jpg'){
    $codigo = str_replace('*', '-', $codigo);
    if ($type == 'file') {
      $url = $this->url_file . $codigo . $ext;
    }else{
      $url = $this->url_img . $codigo . $ext;
    }
    $url_file = '';
    $ch = curl_init( $url ); 
      // Establecer un tiempo de espera
    curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );

    // Establecer NOBODY en true para hacer una solicitud tipo HEAD
    curl_setopt( $ch, CURLOPT_NOBODY, true );
    // Permitir seguir redireccionamientos
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    // Recibir la respuesta como string, no output
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    $data = curl_exec( $ch );

    // Obtener el código de respuesta
    $httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    //cerrar conexión
    curl_close( $ch );

    // Aceptar solo respuesta 200 (Ok), 301 (redirección permanente) o 302 (redirección temporal)
    $accepted_response = array( 200, 301, 302 );
    if( in_array( $httpcode, $accepted_response ) ) {
        $url_file = $url;
    } elseif($type == 'img') {
        $url_file = $this->url_default;
    }
    return $url_file;
  }
}