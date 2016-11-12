<?php

namespace CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StayType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateArrival', 'date')
            ->add('dateDeparture', 'date')
            ->add(
                'city',
                'choice',
                array(
                    'label' => 'City',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'choices' => array(
                        "Auckland" => "Auckland",
                        "Christchurch" => "Christchurch",
                        "Wellington" => "Wellington",
                        "Hamilton" => "Hamilton",
                        "Tauranga" => "Tauranga",
                        "Dunedin" => "Dunedin",
                        "Palmerston North" => "Palmerston North",
                        "Hastings" => "Hastings",
                        "Nelson" => "Nelson",
                        "Napier" => "Napier",
                        "Rotorua " => "Rotorua ",
                        "New Plymouth " => "New Plymouth",
                        "Whangarei" => "Whangarei",
                        "Invercargill" => "Invercargill",
                        "Wanganui" => "Wanganui",
                        "Gisborne " => "Gisborne "
                    ),
                )
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
            'data_class' => 'CommonBundle\Entity\Stay'
        ));
    }
}
