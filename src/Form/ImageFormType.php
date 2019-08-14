<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Image;
use Symfony\Component\Validator\Constraints\File;


class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uploaded_image', FileType::class, ['label' => 'Image',
                            // unmapped means that this field is not associated to any entity property
                            'mapped' => true,

                            // make it optional so you don't have to re-upload the PDF file
                            // everytime you edit the Product details
                            'required' => true,
                        ])
            ->add('height')
            ->add('width');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
