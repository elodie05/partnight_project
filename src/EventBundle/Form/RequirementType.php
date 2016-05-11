<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use EventBundle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManager;

class RequirementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
	private $option = array ();
	
	private $em;
	
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		//$this->options = $options;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	/*->add('item', EntityType::class,array(
        			'class' => 'EventBundle\Entity\Item',
        			'choices' => $this->options['items'],
        			'multiple' => false,
        			'expanded' => false
        	))*/
        	->add('item','text')
            ->add('quantity','integer')
            ->add('save','submit',array(
            		'attr' => array(
            				'class' => 'btn btn-default'
            		)
            ))
        ;
            
            $builder->get('item')->addModelTransformer(new ItemTransformer($this->em));
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
    
}
