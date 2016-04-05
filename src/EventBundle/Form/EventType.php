<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text')
            ->add('location','text')
            ->add('sleepAvailable','integer')
            ->add('sleepTaken','integer')
            ->add('description','text')
            ->add('startTime', 'time',['widget' => 'single_text'])
            ->add('endTime', 'time',['widget' => 'single_text'])
            ->add('date', 'date', ['widget' => 'single_text', 'format' => 'dd-MM-yyyy'])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\Event'
        ));
    }
    
    public function getName()
    {
    	return 'eventbundle_event';
    }
}
