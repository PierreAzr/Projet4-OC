<?php

namespace Louvre\TicketOrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Louvre\TicketOrderBundle\Validator\TicketLimit;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * TicketOrder
 *
 * @ORM\Table(name="ticket_order")
 * @ORM\Entity(repositoryClass="Louvre\TicketOrderBundle\Repository\TicketOrderRepository")
 * @ORM\HasLifecycleCallbacks()
 * @TicketLimit
 */
class TicketOrder
{

  /**
   * @ORM\OneToMany(targetEntity="Louvre\TicketOrderBundle\Entity\Ticket", mappedBy="ticketOrder", cascade={"All"})
   * @Assert\Valid()
   */
  private $tickets;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visitDate", type="datetime")
     * @Assert\DateTime()
     */
    private $visitDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="purchaseDate", type="datetime")
     */
    private $purchaseDate;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nbTickets", type="string", length=255)
     */
    private $typeTicket;

    /**
     * @var int
     *
     * @ORM\Column(name="typeTicket", type="integer")
     */
    private $nbTickets;

    /**
     * @var int
     *
     * @ORM\Column(name="totalPrice", type="integer")
     */
    private $totalPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="orderLvl", type="integer")
     */
    private $orderLvl;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set visitDate
     *
     * @param \DateTime $visitDate
     *
     * @return TicketOrder
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Set purchaseDate
     *
     * @param \DateTime $purchaseDate
     *
     * @return TicketOrder
     */
    public function setPurchaseDate($purchaseDate)
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    /**
     * Get purchaseDate
     *
     * @return \DateTime
     */
    public function getPurchaseDate()
    {
        return $this->purchaseDate;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return TicketOrder
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set typeTicket
     *
     * @param string $typeTicket
     *
     * @return TicketOrder
     */
    public function setTypeTicket($typeTicket)
    {
        $this->typeTicket = $typeTicket;

        return $this;
    }

    /**
     * Get typeTicket
     *
     * @return string
     */
    public function getTypeTicket()
    {
        return $this->typeTicket;
    }

    /**
     * Set nbTickets
     *
     * @param integer $nbTickets
     *
     * @return TicketOrder
     */
    public function setNbTickets($nbTickets)
    {
        $this->nbTickets = $nbTickets;

        return $this;
    }

    /**
     * Get nbTickets
     *
     * @return integer
     */
    public function getNbTickets()
    {
        return $this->nbTickets;
    }

    /**
     * Set totalPrice
     *
     * @param integer $totalPrice
     *
     * @return TicketOrder
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return integer
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set orderLvl
     *
     * @param integer $orderLvl
     *
     * @return TicketOrder
     */
    public function setOrderLvl($orderLvl)
    {
        $this->orderLvl = $orderLvl;

        return $this;
    }

    /**
     * Get orderLvl
     *
     * @return integer
     */
    public function getOrderLvl()
    {
        return $this->orderLvl;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ticket
     *
     * @param \Louvre\TicketOrderBundle\Entity\Ticket $ticket
     *
     * @return TicketOrder
     */
    public function addTicket(\Louvre\TicketOrderBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \Louvre\TicketOrderBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\Louvre\TicketOrderBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @Assert\Callback()
     */
    public function isVisitDate(ExecutionContextInterface $context)
    {
      //vérifie que la date de visite et bonne
      //on recupére la date de visite
      $visitDate = $this->getVisitDate();
      //on recupére la date aujourd'hui à 00:00
      $date = new \DateTime('00:00:00');

          //On verifie que la date n'est pas une date déja passée
          if ($visitDate >= $date){

            // on recupére le jour et le mois de la date de visite
            $monthDate = $visitDate->format('m-d');
            // on recupére le jour de la date de visite
            $day = $visitDate->format('D');

                //si le jour de la date est un mardi
                if ($day == 'Tue') {
                      $context->buildViolation('Le mussée est fermé  le mardis')->addViolation();
                }

                //tableau des jour férié ou le musée et fermée
                $closingDay = ['05-01','11-01','25-12'];

                // Pour chaque jour du tableau on vérifie que la date de visite ne corespond pas à un jour férié
                foreach ($closingDay as $i => $value) {
                    if ($value == $monthDate) {
                      $context->buildViolation('Le mussée est fermé ce jour la')->addViolation();
                    }
                  }

                //Si la date et corespond a celle aujourd'hui on
                if ($date->format('Y-m-d') == $visitDate->format('Y-m-d')) {

                    //on vérifie que s'il est 14h pasée, le  type billet n'est pas du type Journée
                    if ($visitDate > new \DateTime('14:00:00') && $this->getTicketType() == 'Journée'){
                            $context
                        ->buildViolation('il est plus de 14h seul les billets demi-jounée sont disponible pour aujourd\'hui')
                        ->addViolation();
                    }
                    //on vérifie que le musée n'est pas deja fermée
                    if ($visitDate > new \DateTime('18:00:00')){
                            $context
                        ->buildViolation('il est plus de 18h le musée va fermée')
                        ->addViolation();
                    }
                }
          }else{
            $context
           ->buildViolation('La date de visite est une date déja passée')
           ->addViolation();
          }
      }

      /**
       * @Assert\IsTrue()
       */
      public function isTypeTicket()
      {
        //on vérifie que le type de billet est bon
        $type = $this->getTypeTicket();
        if ($type == 'Journée' or $type == 'Demi-journée' ){
          return true;
        }else{
          return false;
        }
      }

      /**
       * @Assert\Callback()
       */
      public function isTickets(ExecutionContextInterface $context)
      {
        //on vérifie qu'il y'a au moins un billet
        if (count($this->getTickets()) == 0 ){
          $context->buildViolation('Il doit y avoir au minimum un billet')->addViolation();
        }
      }

      /**
       * @ORM\PrePersist
       */
      public function FinalCommande()
      {
        //Opreation faite juste avant l'enregistrement dans la base de donnée

        //enregistre le nombre de billets
        $this->setNbTickets(count($this->getTickets()));

        //On calcul le prix des billets selon la date de naissance
        //On recupére les billets
         $tickets = $this->getTickets();
         //on initialise un tableau prix et un entier correspondant au prix total
         $price = [];
         $totalPrice = 0;

            //pour chaque billets
            foreach ($tickets as $i => $value) {

              //on fait la liaison entre le billet et la commande
               $value->setTicketOrder($this);

               //on recupére la date de naissance
               $age = $value->getBirthDate();
               //on calcul la date aujoud'hui déduit de 60 ans (senior)
               $senior = (new \DateTime('now'))->sub(new \DateInterval('P60Y'));
               //on calcul la date aujoud'hui déduit de 12 ans (enfant)
               $child = (new \DateTime('now'))->sub(new \DateInterval('P12Y'));
               //on initialise le champ du tarif
               $priceList='';

                    //Si la date de naissance et supérieur à la date enfant
                    //Alors la personne a moins de 12 ans
                     if($child < $age) {
                         $priceList = 'Enfant';
                         $price[$i] = 8;
                     }
                   //Si la valeur du formulaire de la case tarif réduit et true
                   //Alors la personne a un tarif reduit
                    elseif ($value->getPriceList() == true) {
                        $priceList = 'Réduit';
                        $price[$i] = 10;
                     }
                   //Si la date de naissance et inferieur à la date senior
                   //Alors la personne a plus de 60 ans
                    elseif($senior > $age) {
                        $priceList = 'Senior';
                        $price[$i] = 12;
                    }
                    //Si aucun des cas suivant n'a était verifée
                    //Alors la personne a un tarif normal
                    else {
                       $priceList = 'Normal';
                       $price[$i] = 16;
                    }
              //on lie le prix trouvé au billet
              $value->setPrice($price[$i]);
              //on lie le tarif trouvé au billet
              $value->setPriceList($priceList);
              //on rajoute le prix du billet au prix total
              $totalPrice = $totalPrice + $price[$i];
           }

        //on lie le prix total trouvé à la commande
        $this->setTotalPrice($totalPrice);
        //on lie la date aujourd'hui à la commande
        $this->setPurchaseDate(new \DateTime('now'));
        //on fait passée la commande au niveau un (commande enregistré mais pas payée)
        $this->setOrderLvl(1);

      }

}
