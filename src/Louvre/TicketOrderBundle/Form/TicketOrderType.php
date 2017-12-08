<?php

namespace Louvre\TicketOrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TicketOrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('visitDate', DateType::class, array(
              'widget' => 'single_text',
              'format' => 'dd/MM/yyyy',
          ))
          ->add('email', EmailType::class, array(
          'attr' => array('placeholder' => 'exemple@mail.com')
        ))
          ->add('typeTicket', ChoiceType::class, array(
              'choices'  => array(
                  'Journée' => 'Journée',
                  'Demi-journée' => 'Demi-journée',
                ),
                 'multiple' => false,
                 'required' => true,
                 'data' => 'Journée'
          ))
          ->add('tickets', CollectionType::class, array(
              'entry_type'   => TicketType::class,
              'allow_add'    => true,
               'label' => false,
               'block_name' => 'task_lists',
               'allow_delete' => true
          ))
          ->add('Valider', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketOrderBundle\Entity\TicketOrder'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_ticketorderbundle_ticketorder';
    }


}
