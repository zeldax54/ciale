<?php

namespace GEMA\gemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class BoletinType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('introduccion', CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#00a1e7',
                    'language' => 'es',
                ),
            ))
            ->add('cuerpo', CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#00a1e7',
                    'language' => 'es',
                    'extraPlugins' => 'youtube,slideshow',

                    //...
                ),
            ))
            ->add('fechaboletin','date', array(
                'widget' => 'single_text',
                'label' => 'Fecha del Boletín'
            ))
            ->add('publico','checkbox', array(
                'required' => false
            ))
            ->add('guid',null,array(
                'label_attr'=>array(
                    'style'=>'display:none'
                ),
                'attr'=>array(
                    'style'=>'display:none'
                )
            ))
            ->add('youtube',null,array(
                'label_attr'=>array(
                    'style'=>'display:none'
                ),
                'attr'=>array(
                    'style'=>'display:none',
                    'autocomplete'=>'off'
                ),

            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GEMA\gemaBundle\Entity\Boletin'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_boletin';
    }
}
