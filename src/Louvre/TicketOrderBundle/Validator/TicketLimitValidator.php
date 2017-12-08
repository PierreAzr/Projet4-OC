<?php


namespace Louvre\TicketOrderBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class TicketLimitValidator extends ConstraintValidator
{

  private $requestStack;
  private $em;

  // Les arguments déclarés dans la définition du service arrivent au constructeur
  // On doit les enregistrer dans l'objet pour pouvoir s'en resservir dans la méthode validate()

//  si appel a un service
  public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
  {
    $this->requestStack = $requestStack;
    $this->em           = $em;
  }

  public function validate($value, Constraint $constraint)
  {

    $repository = $this->em->getRepository('LouvreTicketOrderBundle:TicketOrder');
    $list = $repository->findBy(array('visitDate' => $value->getVisitDate()));

    $nbTicket = count($value->getTickets());

       $Total = 0;
       foreach ($list as $ticketOrder) {
          $Total = $Total + $ticketOrder->getNbTickets();
       }
       $totalTicket = $Total + $nbTicket;


     if ($totalTicket > 1000) {
        $this->context->addViolation($constraint->message);
    }

   }

}
