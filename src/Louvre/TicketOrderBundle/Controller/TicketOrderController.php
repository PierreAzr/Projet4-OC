<?php

namespace Louvre\TicketOrderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Louvre\TicketOrderBundle\Entity\TicketOrder;
use Louvre\TicketOrderBundle\Form\TicketOrderType;
use Louvre\TicketOrderBundle\Event\TicketOrderEvent;
use Louvre\TicketOrderBundle\Event\SendMailEvent;

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

            // on enregistre notre commande dans la base de données
            $em = $this->getDoctrine()->getManager();
            $em->persist($ticketOrder);
            $em->flush();

            // on enregistre l'id de la commande dans la session
            $session->set("idTicketOrder", $ticketOrder->getId());

          //on redirige vers la route  payment avec l'id de la commande
          return $this->redirectToRoute("louvre_ticket_order_payment", ["ticketOrder" => $ticketOrder->getId()]);
            }

    return $this->render('LouvreTicketOrderBundle:TicketOrder:index.html.twig', array(
     'form' => $form->createView(),
    ));

  }

  public function paymentAction(TicketOrder $ticketOrder, Request $request)
  {

    $session = $request->getSession();
    $idTicketOrder = $session->get('idTicketOrder');
    $id = $ticketOrder->getId();
    $orderLvl = $ticketOrder->getOrderLvl();

    // on vérifie que le niveau est égal à une commande enregisté mais pas payé
    // on vérifie que l'id de la commande est dans la session
    if($orderLvl == 1 && $idTicketOrder == $id){

      $email = $ticketOrder->getEmail();
      $price = $ticketOrder->getTotalPrice();
      $price = $price * 100;

          if ($request->isMethod('POST'))  {

             \Stripe\Stripe::setApiKey($this->getParameter('stripe_api_key_sk'));
             $token = $request->request->get('stripeToken');

                 // Charge the Customer instead of the card:
                 $charge = \Stripe\Charge::create(array(
                   "amount" => $price,
                   "currency" => "eur",
                   "source" => $token
                 ));

                    //on envoi le mail de confirmation contenant les billets
                    //on crée l'évènement
                    $event = new SendMailEvent($ticketOrder);

                     //on déclenche l'évènement
                     $this->get('event_dispatcher')->dispatch(TicketOrderEvent::NAME,$event);

               //Affiche la page de confirmation
                return $this->render('LouvreTicketOrderBundle:TicketOrder:confirmation.html.twig', array(
                 'ticketOrder' => $ticketOrder,
                ));
             }

        //Affiche la page avec formulaire stripe
        return $this->render('LouvreTicketOrderBundle:TicketOrder:payment.html.twig', array(
          'price' => $price,
          'email' => $email,
          'id' => $id,
          'ticketOrder' => $ticketOrder,
        ));

    }else{
      return $this->redirectToRoute("louvre_ticket_order_homepage");
    }
  }
}
