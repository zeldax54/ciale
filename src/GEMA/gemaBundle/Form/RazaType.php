<?php

namespace GEMA\gemaBundle\Form;

use GEMA\gemaBundle\Entity\TipoRaza;
use GEMA\gemaBundle\Entity\TipoTabla;
use Proxies\__CG__\GEMA\gemaBundle\Entity\Decorador;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use GEMA\gemaBundle\Entity\TipoRazaRepository;
use GEMA\gemaBundle\Entity\TipoTablaRepository;
use GEMA\gemaBundle\Entity\MapaRepository;
use GEMA\gemaBundle\Entity\DecoradorRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RazaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tiporaza','entity',array(
                'class'=>'gemaBundle:TipoRaza',
                'required'=>true,
                'query_builder' => function(TipoRazaRepository $tr) {
                    return $tr->createQueryBuilder('c')
                        ->where("c.tipo is not NULL");

                },
                'label'=>'Tipo de Raza'

            ))

            ->add('mapa','entity',array(
                'class'=>'gemaBundle:Mapa',
                'required'=>true,
                'query_builder' => function(MapaRepository $tr) {
                    return $tr->createQueryBuilder('c')
                        ->where("c.id is not null");

                },
                'label'=>'Mapa(cambiar este atributo puede alterar el proceso de carga)',

            ))

            ->add('nombre')
            ->add('silueta', ChoiceType::class, array(
                'choices'  => array(
                    'silueta_1' => 'Angus, Hereford y el resto de razas',
                    'silueta_2' => 'Braford, Brangus y Brahman',
                ),
            ))
            ->add('descripcion')
            ->add('nombretablagenetica',null,array(
                'attr'=>array(
                    'placeholder'=>'Solo es utilizado en las tablas genéticas de los toros de leche.'
                ),
                'required'=>false
            ))
            ->add('haverazaName',null,array(
                'label'=>'Los toros de esta raza pueden tener un nombre de raza personalizado',
                'label_attr'=>array(
                  'class'=>'col-md-6 control-label'
                ),
                'attr'=>array(

                )
            ))
            ->add('haveactEnlace',null,array(
                'label'=>'Los toros de esta raza pueden ser actualizados por su enlace (actualiza tabla genética)',
                'label_attr'=>array(
                    'class'=>'col-md-6 control-label'
                )
            ))
            ->add('publico')
            ->add('redireccionarraza',null,array(
                'label'=>' Redireccionar Raza',
                'label_attr'=>array(
                    'class'=>'col-md-4 control-label'
                )
            ))
            ->add('redirUrl',null,array(
                'label'=>'Direccionar a esta URL',

            ))
            ->add('redirnewWindow',null,array(
                'label'=>'Redireccionar en ventana nueva',

                'label_attr'=>array(
                    'class'=>'col-md-4 control-label'
                )
            ))

            ->add('tipotabla')
            ->add('decorador','entity',array(
                'class'=>'gemaBundle:Decorador',
                'label_attr'=>array(
                    'class'=>'col-md-4 control-label'
                ),
                'required'=>false,
                'empty_value' => "Sin decorador",
                'query_builder' => function(DecoradorRepository $d) {
                    return $d->createQueryBuilder('c')
                        ->where("c.nombre is not NULL");

                },
                'label'=>'Decorador para los enlaces'

            ))
            ->add('guid',null,array(
                'label_attr'=>array(
                    'style'=>'display:none'
                ),
                'attr'=>array(
                    'style'=>'display:none'
                )
            ))
            ->add('mocho',null,array(
                'label'=>'Mocho(toros de esta raza contienen mocho)'
            ))
            ->add('father',null,array(
                'label'=>'Raza Padre'
            ))
            ->add('tablasmanual',null,array(
                'label'=>'Manual(toros de esta raza con tabla genetica )'
            ))


        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GEMA\gemaBundle\Entity\Raza'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_raza';
    }
}
