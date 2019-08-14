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
        $executionStartTime = microtime(true);


        $image = new Image;
        $form = $this->createForm(ImageFormType::class, $image);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->deleteAllImage();

            $new_height = $form['height']->getData();
            $new_width = $form['width']->getData();


            $em = $this->getDoctrine()->getManager();

            $file = $image->getUploadedImage();

            $image->setWidth($new_width);
            $image->setHeight($new_height);
            
            
            $fileName = $imageUploader->upload($file);

            $base_path = Image::uploadFolder;



            $image->setPath($base_path.$fileName[0]);
            $em->persist($image);

            $em->flush();

            dump($image);



          $executionEndTime = microtime(true);   
 
        $seconds = $executionEndTime - $executionStartTime;
 
        $fileName[4]->info($seconds,['execution time']);

       
        }


        return $this->render('base.html.twig', [
            'form' => $form->createView(),
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
