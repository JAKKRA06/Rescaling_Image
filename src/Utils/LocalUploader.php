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

    public $file;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload($file)
    {
        $image = new Image();

        $image_number = 1;

        $fileName = $image_number.'.'.$file->guessExtension();




        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            //enythink
        }

        $size = getimagesize('./uploads/image/' . $fileName);

        $oryg_image_name = $this->clear(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        $oryg_width = $size[0];
        $oryg_height = $size[1];

        $image_info = getimagesize('./uploads/image/' . $fileName);


        $logger = new Logger('kuba');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/logs/app.log', Logger::DEBUG));
        
        $logger->info($fileName,['file name']);

        $logger->info($image_info[0] . 'x' . $image_info[1], ['width x height']);



        return [$fileName, $oryg_image_name, $oryg_width, $oryg_height, $logger];

    }

    private function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    private function clear($string)
    {
        $string = preg_replace('/[^A-Za-z0-9- ]+/', '', $string);

        return $string;
    }







}