<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PassType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
//        $builder
//                ->add('date', 'datetime', array(
//                    'label' => 'Fecha',
//                    'date_widget' => 'single_text',
//                    'time_widget' => 'single_text',
//                    'format' => 'dd/MM/yyy hh:mm'
//                ))
//                ->add('tank', 'entity', array(
//                    'label' => 'Tanque/Planta Origen',
//                    'placeholder' => 'Elige una opción',
//                    'attr' => array('class' => 'select2', 'style' => "width:100%"),
//                    'class' => 'MainBundle\Entity\Tank'
//                ))
//                ->add('product', 'entity', array(
//                    'label' => 'Producto',
//                    'placeholder' => 'Elige una opción',
//                    'attr' => array('class' => 'select2', 'style' => "width:100%"),
//                    'class' => 'MainBundle\Entity\Product',
//                    'query_builder' => function (ProductRepository $repository) {
//                return $repository->createQueryBuilder('p')
//                        ->where('p.active = ?1')
//                        ->setParameter(1, true);
//                    }
//                ))
//                ->add('tank', 'entity', array(
//                    'label' => 'Tanque/Planta Destino',
//                    'placeholder' => 'Elige una opción',
//                    'attr' => array('class' => 'select2', 'style' => "width:100%"),
//                    'class' => 'MainBundle\Entity\Tank'
//                ))
//                ->add('quantity', 'text', array('label' => 'Cantidad ( l )', 'required' => true))
//        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Pass'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'mainbundle_pass';
    }

}
