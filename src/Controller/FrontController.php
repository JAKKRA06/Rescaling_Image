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

            $this->deleteAllImage();

            $newHeight = $form['height']->getData();
            $newWidth = $form['width']->getData();
            //$newFile = $form['uploaded_image']->getData();


            //$em = $this->getDoctrine()->getManager();

            $file = $image->getUploadedImage();

            $image->setWidth($newWidth);
            $image->setHeight($newHeight);
            
            
            $fileName = $imageUploader->upload($file);

            $base_path = Image::uploadFolder;



            $image->setPath($base_path.$fileName[0]);
            //$em->persist($image);

            //$em->flush();

            dump($fileName[0],$fileName[1], $fileName[2], $fileName[3]);

            //$savedImage = $fileName[5]; //fizyczny obraz
            $newFile = imagecreatefrompng('./uploads/image/1.png');

            $imageWidth = $fileName[2];
            $imageHeight = $fileName[3];

            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($newImage, $newFile, 200, 150, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeight);
            //header("Content-type: image/png");

            imagepng($newImage);





            $executionEndTime = microtime(true);   
 
            $seconds = $executionEndTime - $executionStartTime;
    
            $fileName[4]->info($seconds,['execution time']);

            return $this->render('base.html.twig', [
                'form' => $form->createView(),
                'newImage' => $newImage
            ]);
       
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
