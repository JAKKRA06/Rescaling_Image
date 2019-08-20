<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Image;
use Symfony\Component\Validator\Constraints\File;


class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uploaded_image', FileType::class, ['label' => 'Image [png]',
                            'mapped' => true,
                            'required' => true,
                        ])
            ->add('height')
            ->add('width');
    }

}
