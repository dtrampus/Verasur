<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MainBundle\Entity\ClientRepository;
use MainBundle\Entity\TransportRepository;
use MainBundle\Entity\ProductRepository;

class EgressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('baln','text', array('label'=>'BALN'))
            ->add('date','date', array(
                    'html5' => false,
                    'format' => 'dd/MM/yyy',
                    'widget' => 'single_text',
                    'label'=>'Fecha',
                    
                    'attr' => array( 'id' => 'currDate', 'class' => 'datepicker', 'data-dateformat' => 'dd/mm/yy')
            ))
            ->add('client','entity', array(
                    'label' => 'Cliente',
                    'attr' => array('class' => 'select2', 'style' => 'width:100%'),
                    'placeholder' => 'Elige una opción',
                    'class' => 'MainBundle\Entity\Client',
                    'query_builder' => function (ClientRepository $repository)
                             {
                                 return $repository->createQueryBuilder('c')
                                        ->where('c.active = ?1')
                                        ->setParameter(1, true);
                             }
            ))
            ->add('truckDomain','text', array('label'=>'Dominio Camion'))
            ->add('coupledDomain','text', array('label'=>'Dominio Acoplado'))
            ->add('transport','entity', array(
                    'label' => 'Transporte',
                    'attr' => array('class' => 'select2', 'style' => 'width:100%'),
                    'placeholder' => 'Elige una opción',
                    'class' => 'MainBundle\Entity\Transport',
                    'query_builder' => function (TransportRepository $repository)
                             {
                                 return $repository->createQueryBuilder('t')
                                        ->where('t.active = ?1')
                                        ->setParameter(1, true);
                             }
            ))
            ->add('driver','text', array('label'=>'Chofer'))
            ->add('grossWeight','text', array('label'=>'Peso Bruto'))
            ->add('tareWeight','text', array('label'=>'Peso Tara'))
            ->add('product','entity', array(
                    'label' => 'Producto',
                    'attr' => array('class' => 'select2', 'style' => 'width:100%'),
                    'placeholder' => 'Elige una opción',
                    'class' => 'MainBundle\Entity\Product',
                    'query_builder' => function (ProductRepository $repository)
                             {
                                 return $repository->createQueryBuilder('p')
                                        ->where('p.active = ?1')
                                        ->andWhere('p.typeEntry = ?2')
                                        ->setParameter(1, true)
                                        ->setParameter(2, 'Egreso');
                             }
            ))
            ->add('density','text', array('label'=>'Densidad'))
            ->add('clean','text', array('label'=>'Neto'))
            ->add('realLiter','text', array('label'=>'Litros Reales'))
            ->add('branchNumber','text', array('label'=>'Número'))
            ->add('remitNumber','text', array('label'=>'-'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Egress'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mainbundle_egress';
    }
}
