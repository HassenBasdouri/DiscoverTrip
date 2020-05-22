<?php

namespace App\Form;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;

class EventType extends AbstractType
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('discription',TextareaType::class,array(
                'attr'=>['placeholder'=>'Discription','maxlength'=>'500'],
                'help' => 'A short discription about the event.',
            ))
            ->add('price',MoneyType::class,array(
                'attr'=>['placeholder'=>'Price'],
                'currency'=>"TND",
                'help' => 'The event\'s price in tunisian dinar',
            ))
            ->add('beginningDate',DateTimeType::class,array(
                'help' => 'The event\'s beginnig date',
            ))
            ->add('endingDate',DateTimeType::class,array(
                'help' => 'The event\'s ending date .',
            ))
            ->add('type', EntityType::class, array(
                'class' => 'App\Entity\EventType',
                'choice_label' => 'label',
                'multiple'=>true,
                'block_name' => 'eventtype',
                'help' => 'The event\'s types : you can select many types.',
            ))
            ->add('country', EntityType::class, array(
                    'class' => 'App\Entity\Country',
                    'choice_label' => 'name',
                    'placeholder' => 'Choose a Country',
                    'mapped'=>false,
            ))
            ->add('images', FileType::class, array(
                'mapped'=>false,
                'required'=>false,
                'multiple'=>true,
                'help' => 'The event\'s images : you can select many .',
                'constraints' => [
                 new All( new File([
                     'maxSize' => '2048k',
                     'mimeTypes' => [
                         'image/*',
                     ],
                     'mimeTypesMessage' => 'Please upload a valid image',
                 ]))],
             )
        );
        $builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event){
                $form=$event->getForm();
                dump($form->getData());
                $form->getParent()->add('state',EntityType::class, array(
                    'class' => 'App\Entity\State', 
                    'choice_label' => 'name',
                    'placeholder' => 'Choose a State',
                    'choices'=>$form->getData()->getStates(),
                    "preferred_choices"=>'name'
                ));
            }
        );
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event)
            {
                $form=$event->getForm();
                $data=$event->getData();
                $state=$data->getState();
                if($state){
                    $country=$state->getCountry();
                $form->get('country')->setData($state->getCountry());
                $form->add('state',EntityType::class, array(
                    'class' => 'App\Entity\State', 
                    'choice_label' => 'name',
                    'placeholder' => 'Choose a State',
                    'choices'=>$country->getStates()
                ));
                }else{

                }
                $form->add('state',EntityType::class, array(
                    'class' => 'App\Entity\State', 
                    'choice_label' => 'name',
                    'placeholder' => 'Choose a State',
                    'choices'=>[]
                ));
            }
        );
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
