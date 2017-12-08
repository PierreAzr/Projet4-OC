<?php
namespace Louvre\TicketOrderBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Louvre\TicketOrderBundle\Entity\TicketOrder;

class SendMailEvent extends Event

{
  protected $ticketOrder;


  public function __construct(TicketOrder $ticketOrder)
  {
    $this->ticketOrder = $ticketOrder;
  }

  // Le listener doit avoir accès à la commande
  public function getTicketOrder()
  {
    return $this->ticketOrder;
  }

}





 ?>
