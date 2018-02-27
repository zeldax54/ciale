<?php

namespace GEMA\gemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductosprogramasType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipo', ChoiceType::class, array(
                'choices'  => array(
                    'Producto' => 'Producto',
                    'Programa'=>'Programa',

                ),'label'=>'Tipo'
            ))

            ->add('nombre',null,array(
                'label'=>'Título'
            ))

            ->add('nombremenu',null,array(
                'label'=>'Nombre del menú'
            ))

            ->add('introduccion', CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#00a1e7',
                    'language' => 'es',
                ),
            ))
            ->add('descripcion', CKEditorType::class, array(
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
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GEMA\gemaBundle\Entity\Productosprogramas'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_productosprogramas';
    }
}
