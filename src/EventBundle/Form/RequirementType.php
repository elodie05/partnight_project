<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use EventBundle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RequirementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
	private $option = array ();
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('item', EntityType::class,array(
        			'class' => 'EventBundle\Entity\Item',
        			'choices' => $this->options['items'],
        			'multiple' => false,
        			'expanded' => false
        	))
            ->add('quantity','integer')
            ->add('save','submit')
           
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\Requirement'
        ));
    }
    
    public function __construct(array $options)
    {
    	$this->options = $options;
    }
}
