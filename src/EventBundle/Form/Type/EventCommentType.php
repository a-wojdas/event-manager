<?php

namespace EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType,
    Symfony\Component\Form\Extension\Core\Type\EmailType,
    Symfony\Component\Form\Extension\Core\Type\TextareaType,
    Symfony\Component\Form\Extension\Core\Type\HiddenType,
    Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Forma dodawania komentarza do imprezy.
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
class EventCommentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('description', TextareaType::class, ['required' => false])
                ->add('email', EmailType::class, ['required' => false])
                
                ->add('save', SubmitType::class)
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\EventComment',
            'csrf_protection' => false,
        ));
    }
    
    public function getName() {
        return 'event_comment';
    }

}
