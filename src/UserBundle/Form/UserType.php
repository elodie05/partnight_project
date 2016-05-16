<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName','text',array(
            		'attr' => array(
            				'placeholder' => 'Lastname'
            		)
            		
            ))
            ->add('firstName','text',array(
            		'attr' => array(
            				'placeholder' => 'Firstname'
            		))
            )
            ->add('username','text',array(
            		'attr' => array(
            				'placeholder' => 'Username'
            		))
            )
            ->add('email','email',array(
            		'attr' => array(
            				'placeholder' => 'Email'
            		))
            )
            ->add('password','password',array(
            		'attr' => array(
            				'placeholder' => 'Password'
            		)
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
            'data_class' => 'UserBundle\Entity\User'
        ));
    }
}
