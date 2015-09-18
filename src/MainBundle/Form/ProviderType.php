<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProviderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code','text',array('label'=>'Código'))
            ->add('name','text',array('label'=>'Nombre'))
            ->add('observation','textarea',array('label'=>'Observación', 'required' => false))
            ->add('contact','text',array('label'=>'Contacto'))
            ->add('score','choice',array(
                'label'=>'Puntuación',
                'placeholder' => 'Elige una opción',
                'attr' => array('class' => 'select2', 'style' => "width:100%"),
                'choices' => array(
                    '1'   => '1',
                    '2' => '2',
                    '3'   => '3',
                    '4'   => '4',
                    '5'   => '5',
                    '6'   => '6',
                    '7'   => '7',
                    '8'   => '8',
                    '9'   => '9',
                    '10'   => '10',
                )
                ))
            ->add('category','choice',array(
                'label'=>'Rubro',
                'placeholder' => 'Elige una opción',
                'attr' => array('class' => 'select2', 'style' => "width:100%"),
                'choices' => array(
                    'rubro1'   => 'Rubro 1',
                    'rubro2' => 'Rubro 2',
                    'rubro3'   => 'Rubro 3',
                    'rubro4'   => 'Rubro 4',
                    'rubro5'   => 'Rubro 5',
                )))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Provider'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mainbundle_provider';
    }
}
