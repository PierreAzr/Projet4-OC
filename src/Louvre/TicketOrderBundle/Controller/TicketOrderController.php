<?php

namespace Louvre\TicketOrderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Louvre\TicketOrderBundle\Entity\TicketOrder;
use Louvre\TicketOrderBundle\Form\TicketOrderType;

class TicketOrderController extends Controller
{
  public function indexAction(Request $request)
  {
    //on crée une session
    $session = $request->getSession();

    //on crée une nouvelle commande
    $ticketOrder = new TicketOrder();

    //on crée le formulaire
    $form = $this->get('form.factory')->create(TicketOrderType::class, $ticketOrder);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

                echo "ok";
                exit;
            }

    return $this->render('LouvreTicketOrderBundle:TicketOrder:index.html.twig', array(
     'form' => $form->createView(),
    ));

  }

}
