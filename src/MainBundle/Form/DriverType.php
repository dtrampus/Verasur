<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MainBundle\Entity\TransportRepository;
use MainBundle\Entity\MovementRepository;

class DriverType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('label'=>'Nombre'))
            ->add('surname','text', array('label'=>'Apellido'))
            ->add('dni','text', array('label'=>'Dni'))
            ->add('phone','text', array('label'=>'Telefono','required' => false))
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
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Driver'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mainbundle_driver';
    }
}
