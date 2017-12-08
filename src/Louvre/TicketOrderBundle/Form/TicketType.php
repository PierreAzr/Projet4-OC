<?php

namespace Louvre\TicketOrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('birthDate', BirthdayType::class, array(
            'format' => 'dd MM yyyy',
            'placeholder' => array( 'day' => 'Jour', 'month' => 'Mois', 'year' => 'Année'),
            'years' => range(date('Y') -4, date('Y') -100),
          ))
          ->add('name', TextType::class)
          ->add('firstName', TextType::class)
          ->add('country', CountryType::class, array(
              'preferred_choices' => array('FR'),
          ))
          ->add('priceList',  CheckboxType::class, array(
              'label'    => false,
              'required' => false,
          ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketOrderBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_ticketorderbundle_ticket';
    }


}
