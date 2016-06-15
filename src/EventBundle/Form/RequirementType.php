<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use EventBundle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManager;

class RequirementType extends AbstractType
{
    /**
     * @var ItemTransformer
     */
    private $itemTransformer;

    /**
     * Constructor used to perform dependency injections
     *
     * @param ItemTransformer $itemTransformer
     */
    public function __construct(ItemTransformer $itemTransformer)
    {
        $this->itemTransformer = $itemTransformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('item')
            ->add('quantity','integer')
            ->add('event', EntityType::class, array(
                'class' => 'EventBundle:Event',
                'choice_label' => 'name'
            ))
            ->add('save','submit',array(
                'attr' => array(
                    'class' => 'btn btn-default'
                )
            ))
        ;
            
        $builder->get('item')->addModelTransformer($this->itemTransformer);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\Requirement',
            'csrf_protection' => false
        ));
    }
    
}
