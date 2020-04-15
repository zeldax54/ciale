<?php

namespace GEMA\gemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class MetodopagoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dosismin')
            ->add('dosismax')   
            ->add('tipo', ChoiceType::class, array(
                'choices'  => array(
                    'Contado' => 'Contado',
                    'Tarjeta' => 'Tarjeta',
                    'Cheque' => 'Cheque Pago Diferido',
                ),
            ))        
            ->add('descuentoporc')
            ->add('descrip1')
            ->add('descrip2')
            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GEMA\gemaBundle\Entity\Metodopago'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_metodopago';
    }
}
