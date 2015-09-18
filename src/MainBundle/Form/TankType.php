<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MainBundle\Entity\ProductRepository;

class TankType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('plant', 'checkbox', array(
                    'label' => 'Es Planta?',
                    'required' => false,
                    'attr' => array('class' => 'plant')))
                ->add('code', 'text', array('label' => 'Codigo'))
                ->add('description', 'text', array('label' => 'Descripción'))
                ->add('vertical', 'checkbox', array(
                    'label' => 'Es Vertical?',
                    'required' => false,
                    'attr' => array('class' => 'vertical')))
                ->add('circumference', 'text', array(
                    'label' => 'Circunferencia',
                    'attr' => array('class' => 'circumference')))
                ->add('reference', 'text', array(
                    'label' => 'Punto de Referencia',
                    'attr' => array('class' => 'reference')))
                ->add('coordinates', 'text', array(
                    'label' => 'Punto de Corte',
                    'attr' => array('class' => 'coordinates')))
                ->add('diameter', 'text', array(
                    'label' => 'Diametro Interno (metros)',
                    'attr' => array('readonly' => 'readonly', 'class' => 'diameter')))
                ->add('liter', 'text', array(
                    'label' => 'Litro / CM (litros/centimetros)',
                    'attr' => array('readonly' => 'readonly', 'class' => 'liter')))
                ->add('totalCapacity', 'text', array(
                    'label' => 'Capacidad Total (litros)',
                    'attr' => array('class' => 'totalCapacity')))
                ->add('products', 'entity', array(
                    'label' => 'Producto',
                    'multiple' => true,
                    //'placeholder' => 'Elige una opción',
                    'attr' => array('class' => 'select2', 'placeholder' => 'Elige una opción', 'style' => "width:100%"),
                    'class' => 'MainBundle\Entity\Product',
                    'query_builder' => function (ProductRepository $repository) {
                            return $repository->createQueryBuilder('p')
                                        ->where('p.active = ?1')
                                        ->setParameter(1, true);
                        }
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Tank'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'mainbundle_tank';
    }

}
