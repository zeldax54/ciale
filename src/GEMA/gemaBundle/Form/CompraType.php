<?php

namespace GEMA\gemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompraType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('fecha','date', array(
            'widget' => 'single_text',
            'label' => 'Fecha de compra'
        ))
            ->add('nombre')           
            ->add('apellido')
            ->add('empresa')
            ->add('localidad')
            ->add('provincia')
            ->add('telefono')
            ->add('email')
            ->add('descuento')
            ->add('vendedor')
            ->add('premio')
            ->add('metodopago')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GEMA\gemaBundle\Entity\Compra'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_compra';
    }
}
