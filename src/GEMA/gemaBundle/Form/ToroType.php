<?php

namespace GEMA\gemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use GEMA\gemaBundle\Entity\RazaRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ToroType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */

    protected $idraza;

    public function __construct ($Idraza)
    {
        $this->idraza = $Idraza;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raza','entity',array(
        'class'=>'gemaBundle:Raza',
        'required'=>true,
        'query_builder' => function(RazaRepository $tr) {
            return $tr->createQueryBuilder('c')
                ->where("c.id=".$this->idraza);

        },
        'label'=>'Raza',

    ))
            ->add('nombre',null,array(
                'label'=>'Nombre Real',
                'required'=>true))


            ->add('nacionalidad', ChoiceType::class, array(
                'choices'  => array(
                    'USA' => 'USA',
                    'Canada'=>'Canada',
                    'Argentina' => 'Argentina',
                    'Uruguay' => 'Uruguay',
                    'Paraguay' => 'Paraguay',
                    'Australia' => 'Australia',
                    'Brasil' => 'Brasil',
                    'ARG'=>'ARG'

                ),'label'=>'Nacionalidad'
            ))
            ->add('nombreinterno',null,array(
                'label'=>'Nombre interno o código',
                'required'=>true))
            ->add('apodo')
            ->add('criador')
            ->add('propietario')
            ->add('descripcion',null,array(
                'label_attr'=>array(
                    'class'=>'col-md-4 control-label'
                ),

                'label'=>'Descripción completa del animal',

               ))

            ->add('nuevo',null,array(
                'label'=>'Marcar como Nuevo'
            ))
            ->add('publico',null,array(
                'label'=>'Público'
            ))

            ->add('padre')
            ->add('madre')
            ->add('padrepadre',null,array(
                'label'=>'Abuelo Paterno(Padre del padre)'
            ))
            ->add('madrepadre',null,array('label'=>'Abuela Paterna(Madre del padre)'))

            ->add('padremadre',null,array('label'=>'Abuelo materno(Padre de la madre)'))

            ->add('madremadre',null,array('label'=>'Abuela materna(Madre de la madre)'))

            ->add('padrepadrepadre',null,array('label'=>'Bisabuelo paterno paterno (Padre del padre del padre)'))
            ->add('madrepadrepadre',null,array('label'=>'Bisabuela paterna paterna (Madre del padre del padre)'))
            ->add('padremadrepadre',null,array('label'=>'Bisabuelo materno paterno (Padre de la madre del padre)  '))
            ->add('madremadrepadre',null,array('label'=>'Bisabuela materna paterna (Madre de la madre del padre)'))
            ->add('padrepadremadre',null,array('label'=>'Bisabuelo paterno materna (Padre del padre de la madre)  '))
            ->add('madrepadremadre',null,array('label'=>'Bisabuela paterna materna (Madre del padre de la madre)'))
            ->add('padremadremadre',null,array('label'=>'Bisabuelo materno materno (Padre de la madre de la madre)'))
            ->add('madremadremadre',null,array('label'=>'Bisabuela materna materna (Madre de la madre de la madre)'))

            ->add('evaluaciongenetica',null,array(
                'label'=>'Evaluación Genética'
            ))
            ->add('facilidadparto',null,array(
                'label'=>'Facilidad de parto'
            ))
            ->add('cp', 'checkbox', array(
                'required' => false,
                'label'=>'Concept Plus'
            ))
            ->add('rp',null,array(
                'label'=>'RP'
            ))
            ->add('HBA',null,array(
                'label'=>'HBA'
            ))
            ->add('senasa')
            ->add('fechanacimiento','date', array(
                'widget' => 'single_text',
                'label' => 'Fecha de Nacimiento'
            ))
            ->add('ADN',null,array(
                'label'=>'ADN'
            ))
            ->add('circunferenciaescrotal',null,array(
                'label'=>'Circunferencia escrotal'
            ))
            ->add('largogrupa',null,array(
                'label'=>'Largo de grupa'
            ))
            ->add('anchogrupa',null,array(
                'label'=>'Ancho de grupa'
            ))
            ->add('altogrupa',null,array(
                'label'=>'Alto de grupa'
            ))
            ->add('largocorporal',null,array(
                'label'=>'Largo Corporal'
            ))
            ->add('peso',null,array(
                'label'=>'Peso'
            ))

            ->add('precio',null,array(
                'label'=>'Precio',
                'attr'=>array(
                    'required'=>false
                )
            ))
            ->add('enlacerefexterna',null,array(
                'label'=>'Enlace de referencia externa'
            ))

            ->add('pn1')
            ->add('p205d')
            ->add('p365d')
            ->add('p550d')

            ->add('guid',null,array(
                'label_attr'=>array(
                    'style'=>'display:none'
                ),
                'attr'=>array(
                    'style'=>'display:none'
                )
            ))
            ->add('tablagenetica',null,array(
                'label_attr'=>array(
                    'style'=>'display:none'
                ),
                'attr'=>array(
                    'style'=>'display:none',
                    'autocomplete'=>'off'
                )
            ))


            ->add('lineagenetica')


            ->add('nombreraza',null,array(
                'label'=>'Nombre de Raza(solo par toros sin raza definida por el sistema(Mas Toros))'
            ))

            ->add('tipotablaselected',null,array(
                'label_attr'=>array(
                    'style'=>'display:none'
                ),
                'attr'=>array(
                    'style'=>'display:none'
                )
            ))

            ->add('youtubes', 'collection', array('type' => new YoutubeType()
            ,'allow_add' => true,'by_reference' => false,'allow_delete' => true,)

            );

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GEMA\gemaBundle\Entity\Toro'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_toro';
    }
}
