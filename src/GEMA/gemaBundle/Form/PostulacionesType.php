<?php

namespace GEMA\gemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostulacionesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('fecha','date', array(
                'widget' => 'single_text',
                'label' => 'Fecha'
            ))
            ->add('email')
            ->add('telefono')
            ->add('archivo')
            ->add('oferta','entity',array(
                'class'=>'gemaBundle:OfertaLaboral',
                'required'=>true,

                'label'=>'Oferta',

            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GEMA\gemaBundle\Entity\Postulaciones'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_postulaciones';
    }
}
