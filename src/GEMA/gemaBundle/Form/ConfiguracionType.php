<?php

namespace GEMA\gemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfiguracionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombresitio',null,array(
                'label'=>'Nombre del sitio',

            ))

            ->add('titulopagprinc',null,array(
                'label'=>'Título especial para la página principal',

            ))
            ->add('descripcgeneral',null,array(
                'label'=>'Descripción generl del sitio',

            ))
            ->add('textopie',null,array(
                'label'=>'Texto para el pie del sitio',

            ))
            ->add('urldatafiscalafip',null,array(
                'label'=>'URL al Data Fiscal de AFIP',

            ))
            ->add('urlalta',null,array(
                'label'=>'Alta',

            ))
            ->add('urlkoepon',null,array(
                'label'=>'Koepon',

            ))
            ->add('urlwebmail',null,array(
                'label'=>'Webmail',

            ))
            ->add('urlfacebook',null,array(
                'label'=>'Facebook',

            ))

            ->add('urlgplus',null,array(
                'label'=>'Google Plus',

            ))
            ->add('urlyoutube',null,array(
                'label'=>'Youtube',

            ))
            ->add('urllinkedin',null,array(
                'label'=>'Linkedin',

            ))
            ->add('urlnoticiasinternacionales',null,array(
                'label'=>'Link para las Noticias Internacionales',

            ))
            ->add('coordenadas',null,array(
                'label'=>'Coordenadas Google Maps',

            ))
            ->add('coordenadaslab',null,array(
                'label'=>'Coordenadas del Laboratorio',

            ))
            ->add('enviarmaildestinos',null,array(
                'label'=>'Enviar mail a las direcciones de contacto',

            ))
            ->add('virtualurl')

            ->add('palabrasclave',null,array(
                'label'=>'Palabras Clave',
                'attr'=>array(
                    'placeholder'=>'Palabras clave para los toros sugeridos (separar por coma)'
                )

            ))
            ->add('zoompdf',null,array(
                'label'=>'Zoom pdf',
                'attr'=>array(
                    'placeholder'=>'Valor del zomm del pdf en los catalogos'
                )

            ))

            ->add('registerMailChimp')

            ->add('nombreContacto',null,array(
                'label'=>'Nombre',
                'attr'=>array(
                    'placeholder'=>'Id de la lista de mailChimp de los contactos'
                )

            ))

            ->add('keyContacto',null,array(
                'label'=>'KeyContacto',
                'attr'=>array(
                    'placeholder'=>'Key de la lista de mailChimp de los contactos'
                )

            ))
            ->add('nombreVisita',null,array(
                'label'=>'NombreVisita',
                'attr'=>array(
                    'placeholder'=>'Id de la lista de mailChimp de las visitas'
                )

            ))

            ->add('keyVisita',null,array(
                'label'=>'KeyVisita',
                'attr'=>array(
                    'placeholder'=>'Key de la lista de mailChimp de las visitas'
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
            'data_class' => 'GEMA\gemaBundle\Entity\Configuracion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_configuracion';
    }
}
