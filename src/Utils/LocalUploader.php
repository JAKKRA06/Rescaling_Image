<?php

namespace App\Utils;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
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
        $image_number = 1;

        $fileName = $image_number.'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            //enythink
        }

        $oryg_image_name = $this->clear(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        return [$fileName, $oryg_image_name];

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