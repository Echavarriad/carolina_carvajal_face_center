<?php
namespace App\Library;

class ResizeImage{

	private $image;
	private $width;
	private $height;
	private $imageResized;
	private $extension;

    function __construct($fileName){
        $this->extension = $fileName->getClientOriginalExtension();
        $this->image = $this->openImage($fileName);
        if(!$this->image){            
            return false;
        }
        $this->width = imagesx($this->image);
        $this->height = imagesy($this->image);
    }

    private function openImage($file){
        $file_ = $file->getPathName();
        switch ($this->extension) {
            case 'jpg': case 'jpeg': case 'JPG':
                $img = imagecreatefromjpeg($file_);
                break;
            case 'gif':
                $img = imagecreatefromgif($file_);
                break;
            case 'png':
                $img = imagecreatefrompng($file_);
                break;
            default:
            $img = false;
            break;
        }

        return $img;

    }

 	public function resizeImage($newWidth, $newHeight, $option="auto"){
        // *** Get optimal width and height - based on $option
        $optionArray = $this->getDimensions($newWidth, $newHeight, strtolower($option));
        $optimalWidth  = $optionArray['optimalWidth'];
        $optimalHeight = $optionArray['optimalHeight'];
        if ($optimalWidth > $optimalHeight) {
           $x_initial = 0;
           $y_initial = (($newHeight - $optimalHeight) / 2);
        }else{
            $x_initial = (($newWidth - $optimalWidth) / 2);
            $y_initial = 0;
        }
        
        $this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
        if($this->extension == 'png'){
            imageAlphaBlending($this->imageResized, false);
            imageSaveAlpha($this->imageResized, true);
            $trans = imagecolorallocatealpha($this->imageResized, 0, 0, 0, 127);
            imagefilledrectangle($this->imageResized,0,0,$newWidth,$newHeight,$trans);
        }else{
            $white = imagecolorallocate($this->imageResized , 255, 255, 255);
            imagefilledrectangle($this->imageResized,0,0,$newWidth,$newHeight,$white);
        }

        imagecopyresampled($this->imageResized, $this->image, $x_initial, $y_initial, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
        // *** if option is 'crop', then crop too
        if ($option == 'crop') {
            $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
        }
	}

	private function getDimensions($newWidth, $newHeight, $option){
        switch ($option)
        {
            case 'exact':
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
                break;
            case 'portrait':
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
                break;
            case 'landscape':
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                break;
            case 'auto':
                $optionArray = $this->getSizeByAuto($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
            case 'crop':
                $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];           
                break;
        }

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);

	}

	private function getSizeByFixedHeight($newHeight){
        $ratio = $this->width / $this->height;
        $newWidth = $newHeight * $ratio;

        return $newWidth;
    }
 
    private function getSizeByFixedWidth($newWidth){
        $ratio = $this->height / $this->width;
        $newHeight = $newWidth * $ratio;

        return $newHeight;
    }
 
    private function getSizeByAuto($newWidth, $newHeight){
        if ($this->height < $this->width)
        // *** Image to be resized is wider (landscape)
        {
            $optimalWidth = $newWidth;
            $optimalHeight= $this->getSizeByFixedWidth($newWidth);
        }
        elseif ($this->height > $this->width)
        // *** Image to be resized is taller (portrait)
        {
            $optimalWidth = $this->getSizeByFixedHeight($newHeight);
            $optimalHeight= $newHeight;
        }
        else
        // *** Image to be resizerd is a square
        {
            if ($newHeight < $newWidth) {
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
            } else if ($newHeight > $newWidth) {
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
            } else {
                // *** Sqaure being resized to a square
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
            }
        }
    
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}
 
    private function getOptimalCrop($newWidth, $newHeight){
        
        $heightRatio = $this->height / $newHeight;
        $widthRatio  = $this->width /  $newWidth;
        
        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }
        
        $optimalHeight = $this->height / $optimalRatio;
        $optimalWidth  = $this->width  / $optimalRatio;
        
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

	private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight){
        $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
        $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );
    
        $crop = $this->imageResized;
        $this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
        $white = imagecolorallocate($this->imageResized , 255, 255, 255);
        imagefilledrectangle($this->imageResized,0,0,$newWidth,$newHeight,$white);
        imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight); 
	}	

	public function getResizedImage(){
		return $this->imageResized;
	}

    public function getSizes($width, $height){
        if ($this->width > $width) {
            if($this->width > $this->height){
                $option = 'landscape'; 
            }elseif($this->height > $this->width){
                $option = 'portrait';
            }else{
                $option = 'auto';
            }
        }elseif($this->width > ($width*2)){
            $option = 'landscape';
        }else{
            $option = 'landscape';
        }
        return $option;
    }

    public function getImage(){
		return $this->image;
	}

    public function getWidth(){
		return $this->width;	
	}

    public function getHeight(){
		return $this->height;
	}

    public function getExt(){
		return $this->extension;
	}
}