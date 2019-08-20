<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ImageFormType;
use App\Entity\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Utils\LocalUploader;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;


class FrontController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(Request $request, LocalUploader $imageUploader)
    {
        
        $this->deleteAllImage();

        $executionStartTime = microtime(true);


        $image = new Image;
        $form = $this->createForm(ImageFormType::class, $image);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            //$this->deleteAllImage();

            $newHeight = $form['height']->getData();
            $newWidth = $form['width']->getData();

            $file = $image->getUploadedImage();

            $image->setWidth($newWidth);
            $image->setHeight($newHeight);
            
            
            $fileName = $imageUploader->upload($file, $newHeight, $newWidth);


            $executionEndTime = microtime(true);   
 
            $seconds = $executionEndTime - $executionStartTime;
    
            $fileName[1]->info($seconds,['execution time [s]']);
       
        }


        return $this->render('base.html.twig', [
            'form' => $form->createView()
        ]);
    }


    private function deleteAllImage()
    {
        $folder = 'uploads/image';

        $files = glob($folder . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        
    }
}
