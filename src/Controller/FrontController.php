<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ImageFormType;
use App\Entity\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Utils\LocalUploader;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(Request $request, LocalUploader $imageUploader)
    {
        $image = new Image;
        $form = $this->createForm(ImageFormType::class, $image);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->deleteAllImage();


            $uploaded_image = $form['uploaded_image']->getData();
            $new_height = $form['height']->getData();
            $new_width = $form['width']->getData();


            $em = $this->getDoctrine()->getManager();

            $file = $image->getUploadedImage();

            $fileName = $imageUploader->upload($file);

            $base_path = Image::uploadFolder;


            $image->setWidth($new_width);
            $image->setHeight($new_height);
            $image->setPath($base_path.$fileName[0]);
            $em->persist($image);

            $em->flush();

            
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
