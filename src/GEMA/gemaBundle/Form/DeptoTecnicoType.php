<?php

namespace GEMA\gemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use GEMA\gemaBundle\Entity\ProvinciaRepository;

class DeptoTecnicoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cargo')
            ->add('nombre')
            ->add('telefono')
            ->add('email')
            ->add('publico')
            ->add('guid',null,array(
                'label_attr'=>array(
                    'style'=>'display:none'
                ),
                'attr'=>array(
                    'style'=>'display:none'
                )
            ))
            ->add('provincia','entity',array(
                'class'=>'gemaBundle:Provincia',
                'required'=>true,
                'query_builder' => function(ProvinciaRepository $pr) {
                    return $pr->createQueryBuilder('c')
                        ->where("c.casacentral=true");

                },

                'label_attr'=>array(
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
            'data_class' => 'GEMA\gemaBundle\Entity\DeptoTecnico'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_deptotecnico';
    }
}
