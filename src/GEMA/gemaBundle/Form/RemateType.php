<?php

namespace GEMA\gemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RemateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('fecha','date', array(
                'widget' => 'single_text',
                'label' => 'Fecha'
            ))
            ->add('localidad',null,array(


            ))
            ->add('provincia',null,array(


            ))
            ->add('sitioweb',null,array(


            ))
            ->add('linksitioweb',null,array(
                'attr'=>array(
                    'required'=>false
                )

            ))
            ->add('contacto',null,array(


            ))
            ->add('linkflyer',null,array(


            ))
            ->add('linkcatalogo',null,array(


            ))

            ->add('guid',null,array(
                'label_attr'=>array(
                    'style'=>'display:none'
                ),
                'attr'=>array(
                    'style'=>'display:none'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GEMA\gemaBundle\Entity\Remate'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_remate';
    }
}
