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
            ->add('street','text')
            ->add('zipcode','text')
            ->add('city','text')
            ->add('sleepAvailable','integer')
            ->add('description','textarea',array(
            		'required' => false
            ))
            ->add('startdate', 'datetime', array(
                                                'widget' => 'single_text',
                                                'input' => 'datetime',
                                                'format' => 'MM/dd/yyyy',
                                                'attr' => array('class' => 'date' /*'timepicker'*/),
                                                ))
            ->add('endDate', 'datetime', array(
                                                'widget' => 'single_text',
                                                'input' => 'datetime',
                                                'format' => 'MM/dd/yyyy',
                                                'attr' => array('class' => 'date'),
                                                ))
            ->add('save','submit',array(
            		'attr' => array(
            				'class' => 'btn btn-default'
            		)
            ))
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
