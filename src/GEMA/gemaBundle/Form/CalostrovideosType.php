<?php

namespace GEMA\gemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CalostrovideosType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('section', ChoiceType::class, array(
            'choices'  => array(
                'CALOSTRO' => 'CALOSTRO',
                'BEEFCOMP' => 'BEEFCOMP',
                
            ),
        ))
            ->add('titulo')
            ->add('videoby')
            ->add('urlvideo')
            ->add('urlbackimg')
            ->add('urlminiatura')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GEMA\gemaBundle\Entity\Calostrovideos'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gema_gemabundle_calostrovideos';
    }
}
