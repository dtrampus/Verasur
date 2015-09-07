<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MainBundle\Entity\ProviderRepository;
use MainBundle\Entity\TransportRepository;
use MainBundle\Entity\DriverRepository;
use MainBundle\Entity\ProductRepository;
use Symfony\Component\Validator\Constraints\DateTime;

class IngressType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('baln', 'text', array('label' => 'BALN', 'required' => false))
                ->add('date', 'datetime', array(
                    'label' => 'Fecha',
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'format' => 'dd/MM/yyy hh:mm'
                ))
//            ->add('time', 'text', array(
//                'label'=>'Hora',
//                'attr' => array('class' => 'timepicker')
//            ))
                ->add('provider', 'entity', array(
                    'label' => 'Proveedor',
                    'placeholder' => 'Elige una opción',
                    'attr' => array('class' => 'select2', 'style' => "width:100%"),
                    'class' => 'MainBundle\Entity\Provider',
                    'query_builder' => function (ProviderRepository $repository) {
                return $repository->createQueryBuilder('p')
                        ->where('p.active = ?1')
                        ->setParameter(1, true);
            }
                ))
                ->add('truckDomain', 'text', array('label' => 'Camión'))
                ->add('coupledDomain', 'text', array('label' => 'Acoplado'))
                ->add('transport', 'entity', array(
                    'label' => 'Transporte',
                    'placeholder' => 'Elige una opción',
                    'attr' => array('class' => 'select2', 'style' => "width:100%"),
                    'class' => 'MainBundle\Entity\Transport',
                    'query_builder' => function (TransportRepository $repository) {
                return $repository->createQueryBuilder('p')
                        ->where('p.active = ?1')
                        ->setParameter(1, true);
            }
                ))
                ->add('driver', 'entity', array(
                    'label' => 'Chofer',
                    'placeholder' => 'Elige una opción',
                    'attr' => array('class' => 'select2', 'style' => "width:100%"),
                    'class' => 'MainBundle\Entity\Driver'
                ))
                ->add('grossWeight', 'text', array('label' => 'Peso Bruto'))
                ->add('tareWeight', 'text', array('label' => 'Tara'))
                ->add('product', 'entity', array(
                    'label' => 'Producto',
                    'placeholder' => 'Elige una opción',
                    'attr' => array('class' => 'select2', 'style' => "width:100%"),
                    'class' => 'MainBundle\Entity\Product',
                    'query_builder' => function (ProductRepository $repository) {
                return $repository->createQueryBuilder('p')
                        ->where('p.active = ?1')
                        ->setParameter(1, true);
            }
                ))
                ->add('density', 'text', array('label' => 'Densidad corregida a 15'))
                ->add('tested', 'checkbox', array('label' => 'Densidad analizada', 'required' => false))
                ->add('clean', 'text', array(
                    'label' => 'Neto',
                    'attr' => array('readonly' => true)
                ))
                ->add('realLiter', 'text', array(
                    'label' => 'Litros Reales',
                    'attr' => array('readonly' => true)
                ))
                ->add('branchNumber', 'text', array('label' => 'Número de sucursal', 'required' => false))
                ->add('remitNumber', 'text', array('label' => 'Número de remito', 'required' => false))
                ->add('observation', 'textarea', array('label' => 'Observación', 'required' => false))
                ->add('distillationGout', 'text', array('label' => 'Destilación la gota', 'required' => false))
                ->add('fivePercent', 'text', array('label' => '5%', 'required' => false))
                ->add('tenPercent', 'text', array('label' => '10%', 'required' => false))
                ->add('twentyPercent', 'text', array('label' => '20%', 'required' => false))
                ->add('thirtyPercent', 'text', array('label' => '30%', 'required' => false))
                ->add('fortyPercent', 'text', array('label' => '40%', 'required' => false))
                ->add('fiftyPercent', 'text', array('label' => '50%', 'required' => false))
                ->add('sixtyPercent', 'text', array('label' => '60%', 'required' => false))
                ->add('seventyPercent', 'text', array('label' => '70%', 'required' => false))
                ->add('eightyPercent', 'text', array('label' => '80%', 'required' => false))
                ->add('ninetyPercent', 'text', array('label' => '90%', 'required' => false))
                ->add('ninetyFivePercent', 'text', array('label' => '95%', 'required' => false))
                ->add('pDry', 'text', array('label' => 'P. Seco', 'required' => false))
                ->add('pFinal', 'text', array('label' => 'P. Final', 'required' => false))
                ->add('recovered', 'text', array('label' => 'Recuperado', 'required' => false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Ingress'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'mainbundle_ingress';
    }

}
