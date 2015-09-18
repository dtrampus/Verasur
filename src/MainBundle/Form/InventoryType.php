<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MainBundle\Entity\ProductRepository;
use MainBundle\Entity\Tank;

class InventoryType extends AbstractType {

    protected $tank;

    public function __construct(Tank $tank) {
        $this->tank = $tank;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
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
                    'attr' => array('class' => 'select2', 'style' => "width:100%"),
                    'class' => 'MainBundle\Entity\Product',
                    'query_builder' => function (ProductRepository $repository) {
                        return $repository->createQueryBuilder('p')
                                    ->join('p.tanks', 't')
                                    ->where('p.active = ?1')
                                    ->andWhere('t.id = ?2')
                                    ->setParameter(1, true)
                                    ->setParameter(2, $this->tank->getId());
                    }
                ))
                ->add('vacuum', 'text', array(
                    'label' => 'CM de Vacio',
                    'attr' => array('class' => 'vacuum')))
                ->add('liter', 'text', array(
                    'label' => 'Volumen (litros)',
                    'attr' => array('class' => 'liter')))
                ->add('observation', 'textarea', array('label' => 'Observación', 'required' => false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Inventory'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'mainbundle_inventory';
    }

}
