<?php

namespace GEMA\gemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class NoticiaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoria',null,array(

                    'required'=>true

            ))
            ->add('titulo')
            ->add('fechanoticia','date', array(
                'widget' => 'single_text',
                'label' => 'Fecha de la Noticia'
            ))
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
                ),
            ))

            ->add('publico')
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
            'data_class' => 'GEMA\gemaBundle\Entity\Noticia'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_noticia';
    }
}
