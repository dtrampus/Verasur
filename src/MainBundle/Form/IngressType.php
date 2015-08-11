<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MainBundle\Entity\ProviderRepository;
use MainBundle\Entity\TransportRepository;
use MainBundle\Entity\ProductRepository;
use Symfony\Component\Validator\Constraints\DateTime;

class IngressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('baln','text',array('label'=>'BALN'))
            ->add('date','date',array(
                'label'=>'Fecha',
                'html5' => false,
                'format' => 'dd/MM/yyy',
                'widget' => 'single_text',
                'attr' => array('class' => 'datepicker', "data-dateformat" => "dd/mm/yy")
            ))
            ->add('provider','entity',array(
                'label'=>'Proveedor',
                'placeholder' => 'Elige una opción',
                'attr' => array('class' => 'select2', 'style' => "width:100%"),
                'class' => 'MainBundle\Entity\Provider',
                'query_builder' => function (ProviderRepository $repository)
                             {
                                 return $repository->createQueryBuilder('p')
                                        ->where('p.active = ?1')
                                        ->setParameter(1, true);
                             }
                
            ))
            ->add('truckDomain','text',array('label'=>'Dominio de camión'))
            ->add('coupledDomain','text',array('label'=>'Dominio de acoplado'))
            ->add('transport','entity',array(
                'label'=>'Transporte',
                'placeholder' => 'Elige una opción',
                'attr' => array('class' => 'select2', 'style' => "width:100%"),
                'class' => 'MainBundle\Entity\Transport',
                'query_builder' => function (TransportRepository $repository)
                             {
                                 return $repository->createQueryBuilder('p')
                                        ->where('p.active = ?1')
                                        ->setParameter(1, true);
                             }
            ))
            ->add('driver','text',array('label'=>'Chofer'))
            ->add('grossWeight','text',array('label'=>'Peso Bruto'))
            ->add('tareWeight','text',array('label'=>'Tara'))
            ->add('product','entity',array(
                'label' => 'Producto',
                'placeholder' => 'Elige una opción',
                'attr' => array('class' => 'select2', 'style' => "width:100%"),
                'class' => 'MainBundle\Entity\Product',
                'query_builder' => function (ProductRepository $repository)
                         {
                             return $repository->createQueryBuilder('p')
                                    ->where('p.active = ?1')
                                    ->setParameter(1, true);
                         }
            ))
            ->add('density','text',array('label'=>'Densidad'))
            ->add('clean','text',array('label'=>'Neto'))
            ->add('realLiter','text',array('label'=>'Litros Reales'))   
            ->add('branchNumber','text',array('label'=>'Número'))
            ->add('remitNumber','text',array('label'=>'-'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Ingress'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mainbundle_ingress';
    }
}