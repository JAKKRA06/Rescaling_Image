<?php

namespace App\Utils;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use App\Entity\Image;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;


class LocalUploader 
{

    private $targetDirectory;
    private $newTargetDirectory;

    public $file;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload($file,  $newHeight,  $newWidth)
    {
        $image = new Image();

        $image_number = 1;

        $fileName = $image_number.'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            //
        }

        $size = getimagesize('./uploads/image/1.png');
        
        $loadedFile = imagecreatefrompng('./uploads/image/1.png');

        $imageWidth = $size[0];
        $imageHeight = $size[1]; 



        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresized($newImage, $loadedFile, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeight);
        
        $save = './uploads/image/1.png';

        chmod($save, 0755);

        imagepng($newImage, $save,0,NULL);

        imagedestroy($newImage);



        $logger = new Logger('Kuba');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/logs/app.log', Logger::DEBUG));
        
        $logger->info($fileName,['file name']);

        $logger->info($newWidth . 'x' . $newHeight, ['width x height']);


        return [$fileName, $logger, $file, $newImage];

    }

    private function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    private function getNewTargetDirectory()
    {
        return $this->newTargetDirectory;
    }

    private function clear($string)
    {
        $string = preg_replace('/[^A-Za-z0-9- ]+/', '', $string);

        return $string;
    }







}