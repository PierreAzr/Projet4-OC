<?php

namespace Louvre\TicketOrderBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class TicketLimit extends Constraint
{
    public $message = "Il n'y a plus de billet disponible pour pour la journée voulu.";

//si appel a un service
    public function validatedBy()
    {
      return 'louvre_ticket_order_ticketlimit'; // Ici, on fait appel à l'alias du service
    }

//permet de mettre une validation sur la class
 public function getTargets()
 {
     return self::CLASS_CONSTRAINT;
 }
}


 ?>
