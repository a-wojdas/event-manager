<?php

namespace EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType,
    Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Forma wyszukiwania imprez względem odległości od lokalizacji.
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
class EventSearchType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('address', TextType::class, ['mapped' => false])                
                ->add('search', SubmitType::class)
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
//            'data_class' => 'EventBundle\Entity\Event',
            'csrf_protection' => false,
        ));
    }
    
    public function getName() {
        return 'event_search';
    }

}
