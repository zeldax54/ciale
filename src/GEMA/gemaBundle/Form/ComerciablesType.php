<?php

namespace GEMA\gemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ComerciablesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('guid',null,array(
                'label_attr'=>array(
                    'style'=>'display:none'
                ),
                'attr'=>array(
                    'style'=>'display:none'
                )
            ))
            ->add('titulo')
            ->add('tipo', ChoiceType::class, array(
                'choices'  => array(
                    'promocion' => 'Promocion',
                    'merchandising'=>'Merchandising',    
                    'producto'=>'Producto',
                    'reciennacidos'=>'Recién nacidos',     

                ),'label'=>'Tipo',                
            ))
            ->add('categoria',ChoiceType::class,array(
                'label'=>'Categoria',
                'required'=>false,
                'choices'  => array(                            
                    'materialesia' => 'Materiales IA',
                    'ide'=>'IDE',
                    'pesaje'=>'Pesaje'                   
                )
            ))
            ->add('fechainicio','date', array(
                'widget' => 'single_text',
                'label' => 'Fecha de Inicio',
                'required' => false
            ))
            ->add('fechafin','date', array(
                'widget' => 'single_text',
                'label' => 'Fecha de Fin',
                'required' => false
            ))
            ->add('precio')
            ->add('descripcion')
            ->add('videos',null,array(
                'label'=>'Videos(si son varios separelos por ;)'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GEMA\gemaBundle\Entity\Comerciables'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_comerciables';
    }
}
