<?php

namespace EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType,
    Symfony\Component\Form\Extension\Core\Type\DateType,
    Symfony\Component\Form\Extension\Core\Type\EmailType,
    Symfony\Component\Form\Extension\Core\Type\TextareaType,
    Symfony\Component\Form\Extension\Core\Type\HiddenType,
    Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Forma dodawania nowej imprezy.
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
class EventType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('latitude', HiddenType::class, ['mapped' => false])
                ->add('longitude', HiddenType::class, ['mapped' => false])
                ->add('name', TextType::class)
                ->add('description', TextareaType::class)
                ->add('address', TextType::class)
                ->add('email', EmailType::class)
                ->add('start', DateType::class, ['widget' => 'single_text'])
                ->add('finish', DateType::class, ['widget' => 'single_text'])
                
                ->add('save', SubmitType::class)
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\Event',
            'csrf_protection' => false,
        ));
    }
    
    public function getName() {
        return 'event';
    }

}
