<?php

namespace app\classes;

use Exception;

class Image
{
    private string $fileName; 
    private string $fileTemp; 
    private string $extension; 
    private int|float $width; 
    private int|float $height; 
    private array $acceptedExtensions = ['jpg', 'jpeg', 'png'];
    
    public function __construct(array $image)
    {
        $this->fileName  = $image['file']['name'];
        $this->fileTemp  = $image['file']['tmp_name'];
        $this->extension = pathinfo($this->fileName, PATHINFO_EXTENSION);
        list($this->width, $this->height) = getimagesize($this->fileTemp);
    }

    public function upload(int|float $widthToResize, string $folder)
    {
        if(!in_array($this->extension, $this->acceptedExtensions)){
            throw new Exception("Extensão não aceita");            
        }
        
        $heightToResize = ceil($this->height * ($widthToResize / $this->width));   
        $newName = time();

        switch ($this->extension) {
            case 'jpeg':
            case 'jpg':
                $fromJpeg   = imagecreatefromjpeg($this->fileTemp);
                $imageLayer = imagecreatetruecolor($widthToResize, $heightToResize);
                imagecopyresampled($imageLayer, $fromJpeg, 0, 0, 0, 0, $widthToResize, $heightToResize, $this->width, $this->height);
                imagejpeg($imageLayer, './' . $folder . '/' . $newName . '.' . $this->extension);
                break;
            case 'png':
                $fromPng    = imagecreatefrompng($this->fileTemp);
                $imageLayer = imagecreatetruecolor($widthToResize, $heightToResize);
                imagecopyresampled($imageLayer, $fromPng, 0, 0, 0, 0, $widthToResize, $heightToResize, $this->width, $this->height);
                imagepng($imageLayer, './' . $folder . '/' . $newName . '.' . $this->extension);
                break;

            default:
                throw new Exception("Extensão não aceita");                
                break;
        }
    }
}