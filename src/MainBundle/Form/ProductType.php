<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code','text',array('label'=>'Código'))
            ->add('description','text',array('label'=>'Descripción'))
            ->add('numFormulaProduction','text',array('label'=>'Nro. Fórmula Producción'))
            ->add('crudeLar','checkbox',array('label'=>'Cálculo Crudo-Lar', 'required' => false))
            ->add('typeEntry','choice',array('label' => 'Ingreso/Egreso','placeholder' => 'Elige una opción','choices'  => array('Ingreso' => 'Ingreso', 'Egreso' => 'Egreso')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mainbundle_product';
    }
}
