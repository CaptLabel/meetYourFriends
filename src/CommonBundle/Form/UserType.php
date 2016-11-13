<?php

namespace CommonBundle\Form;

use CommonBundle\Form\Type\InputTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                'text',
                array(
                    'label' => 'Email adresse',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )->add(
                'name',
                'text',
                array(
                    'label' => 'Name',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'password',
                'password',
                array(
                    'label' => 'Password',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )->add(
                'key_secure',
                'hidden'
            )->add(
                'submit',
                'submit',
                array(
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )
                )
            )
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CommonBundle\Entity\User'
        ));
    }
}
