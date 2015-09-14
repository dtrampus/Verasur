<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MainBundle\Entity\ClientRepository;
use MainBundle\Entity\TransportRepository;
use MainBundle\Entity\ProductRepository;

class EgressType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $nowDate = new \DateTime();
        $builder
                ->add('baln', 'text', array('label' => 'BALN', 'required' => false))
                ->add('date', 'datetime', array(
                    'label' => 'Fecha',
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'format' => 'dd/MM/yyy hh:mm',
                    'data' => ($options['data']->getDate() != null ? $options['data']->getDate() : $nowDate)
                ))
//            ->add('time', 'text', array(
//                'label'=>'Hora',
//                'attr' => array('class' => 'timepicker')
//            ))                
                ->add('client', 'entity', array(
                    'label' => 'Nombre',
                    'attr' => array('class' => 'select2', 'style' => 'width:100%'),
                    'placeholder' => 'Elige una opción',
                    'class' => 'MainBundle\Entity\Client',
                    'query_builder' => function (ClientRepository $repository) {
                return $repository->createQueryBuilder('c')
                        ->where('c.active = ?1')
                        ->setParameter(1, true);
            }
                ))
                ->add('truckDomain', 'text', array('label' => 'Camion'))
                ->add('coupledDomain', 'text', array('label' => 'Acoplado'))
                ->add('transport', 'entity', array(
                    'label' => 'Nombre',
                    'attr' => array('class' => 'select2', 'style' => 'width:100%'),
                    'placeholder' => 'Elige una opción',
                    'class' => 'MainBundle\Entity\Transport',
                    'query_builder' => function (TransportRepository $repository) {
                return $repository->createQueryBuilder('t')
                        ->where('t.active = ?1')
                        ->setParameter(1, true);
            }
                ))
                ->add('driver', 'entity', array(
                    'label' => 'Chofer',
                    'placeholder' => 'Elige una opción',
                    'attr' => array('class' => 'select2', 'style' => "width:100%"),
                    'class' => 'MainBundle\Entity\Driver'
                ))
                ->add('grossWeight', 'text', array('label' => 'Peso Bruto (Kilogramos)'))
                ->add('tareWeight', 'text', array('label' => 'Tara (Kilogramos)'))
                ->add('product', 'entity', array(
                    'label' => 'Nombre',
                    'attr' => array('class' => 'select2', 'style' => 'width:100%'),
                    'placeholder' => 'Elige una opción',
                    'class' => 'MainBundle\Entity\Product',
                    'query_builder' => function (ProductRepository $repository) {
                return $repository->createQueryBuilder('p')
                        ->where('p.active = ?1')
                        ->setParameter(1, true);
            }
                ))
                ->add('density', 'text', array('label' => 'Densidad corregida a 15'))
                ->add('tested', 'checkbox', array('label' => 'Fue analizada?', 'required' => false))
                ->add('clean', 'text', array(
                    'label' => 'Neto (Kilogramos)',
                    'attr' => array('readonly' => true)
                ))
                ->add('realLiter', 'text', array(
                    'label' => 'Litros Reales',
                    'attr' => array('readonly' => true)
                ))
                ->add('branchNumber', 'text', array('label' => 'Número de sucursal', 'required' => false))
                ->add('remitNumber', 'text', array('label' => 'Número de remito', 'required' => false))
                ->add('observation', 'textarea', array('label' => 'Observación', 'required' => false))
                ->add('distillationGout', 'text', array('label' => 'Destilación la gota (Tº)', 'required' => false))
                ->add('fivePercent', 'text', array('label' => '5% (Tº)', 'required' => false))
                ->add('tenPercent', 'text', array('label' => '10% (Tº)', 'required' => false))
                ->add('twentyPercent', 'text', array('label' => '20% (Tº)', 'required' => false))
                ->add('thirtyPercent', 'text', array('label' => '30% (Tº)', 'required' => false))
                ->add('fortyPercent', 'text', array('label' => '40% (Tº)', 'required' => false))
                ->add('fiftyPercent', 'text', array('label' => '50% (Tº)', 'required' => false))
                ->add('sixtyPercent', 'text', array('label' => '60% (Tº)', 'required' => false))
                ->add('seventyPercent', 'text', array('label' => '70% (Tº)', 'required' => false))
                ->add('eightyPercent', 'text', array('label' => '80% (Tº)', 'required' => false))
                ->add('ninetyPercent', 'text', array('label' => '90% (Tº)', 'required' => false))
                ->add('ninetyFivePercent', 'text', array('label' => '95% (Tº)', 'required' => false))
                ->add('pDry', 'text', array('label' => 'P. Seco (Tº)', 'required' => false))
                ->add('pFinal', 'text', array('label' => 'P. Final (Tº)', 'required' => false))
                ->add('recovered', 'text', array('label' => 'Recuperado (Porcentaje)', 'required' => false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Egress'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'mainbundle_egress';
    }

}
