<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ParticipationType extends AbstractType
{
	private $options;
	
	public function __construct(array $options = null){
		$this->options = $options;
	}
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('users',EntityType::class,array(
            		'class' => 'UserBundle:User',
            		'choices' => $this->options['users'],
            		'multiple' => true,
            		'expanded' => true
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
     /*   $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\Participation'
        ));*/
    }
}
