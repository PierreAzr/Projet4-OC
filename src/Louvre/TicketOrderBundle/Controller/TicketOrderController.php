<?php

namespace Louvre\TicketOrderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicketOrderController extends Controller
{
  public function indexAction()
  {
      return $this->render('LouvreTicketOrderBundle:TicketOrder:index.html.twig');
  }

}
