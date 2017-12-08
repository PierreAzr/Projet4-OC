<?php

namespace Louvre\TicketOrderBundle\Mail;

use Louvre\TicketOrderBundle\Event\SendMailEvent;

class MailListener
{
  /**
    * @var MailAdd
    */
  protected $mailAdd;

  public function __construct(MailAdd $mailAdd)
  {
    $this->mailAdd = $mailAdd;
  }

  public function processMail(SendMailEvent $event)
  {
    $this->mailAdd->SendMailAct($event);
  }

}

 ?>
