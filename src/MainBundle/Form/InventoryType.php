<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MainBundle\Entity\ProductRepository;


class InventoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $nowDate = new \DateTime;
        $builder
                ->add('date', 'datetime', array(
                    'label' => false,
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'format' => 'dd/MM/yyy hh:mm',
                    'data' => ($options['data']->getDate() != null ? $options['data']->getDate() : $nowDate)
                ))
                ->add('product', 'entity', array(
                    'label' => 'Producto',
                    'placeholder' => 'Elige una opción',
                    'attr' => array('class' => 'select2', 'placeholder' => 'Elige una opción', 'style' => "width:100%"),
                    'class' => 'MainBundle\Entity\Product',
                    'query_builder' => function (ProductRepository $repository) {
                            return $repository->createQueryBuilder('p')
                                        ->where('p.active = ?1')
                                        ->setParameter(1, true);
                        }
                ))
                ->add('vacuum', 'text', array(
                    'label' => 'CM de Vacio',
                    'attr' => array('class' => 'vacuum')))
                ->add('liter', 'text', array(
                    'label' => 'Litros/CM',
                    'attr' => array('class' => 'liter')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Inventory'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mainbundle_inventory';
    }
}
