<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('image', FileType::class, array(
            'label' => false,
            'mapped'=>false,
            'required'=>true,
            'label'=>'Select an image',
            'multiple'=>false,
            'constraints' => [
             new File([
                 'maxSize' => '1024k',
                 'mimeTypes' => [
                     'image/jpeg',
                     'image/jpg',
                     'image/png',
                 ],
                 'mimeTypesMessage' => 'Please upload a valid image',
             ])],
         ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
