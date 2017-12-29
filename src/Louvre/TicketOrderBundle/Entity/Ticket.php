<?php

namespace Louvre\TicketOrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="Louvre\TicketOrderBundle\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\ManyToOne(targetEntity="Louvre\TicketOrderBundle\Entity\TicketOrder", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticketOrder;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Au minimum deux caractère.")
     * @Assert\NotBlank(message="ce champ ne doit pas être vide")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Au minimum deux caractère.")
     * @Assert\NotBlank(message="ce champ ne doit pas être vide")
     */
    private $firstName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthDate", type="datetime")
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\Country()
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="priceList", type="string", length=255)
     */
    private $priceList;

    /**
     * @var int
     *
     * @ORM\Column(name="Price", type="integer")
     */
    private $price;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Ticket
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Ticket
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Ticket
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Ticket
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set priceList
     *
     * @param string $priceList
     *
     * @return Ticket
     */
    public function setPriceList($priceList)
    {
        $this->priceList = $priceList;

        return $this;
    }

    /**
     * Get priceList
     *
     * @return string
     */
    public function getPriceList()
    {
        return $this->priceList;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set ticketOrder
     *
     * @param \Louvre\TicketOrderBundle\Entity\TicketOrder $ticketOrder
     *
     * @return Ticket
     */
    public function setTicketOrder(\Louvre\TicketOrderBundle\Entity\TicketOrder $ticketOrder)
    {
        $this->ticketOrder = $ticketOrder;

        return $this;
    }

    /**
     * Get ticketOrder
     *
     * @return \Louvre\TicketOrderBundle\Entity\TicketOrder
     */
    public function getTicketOrder()
    {
        return $this->ticketOrder;
    }

    /**
     * @Assert\IsTrue()
     */
    public function isBirthDate()
    {
      //on enleve 4 ans car les billets sont gratuit pour un age inferieur à 4ans
      $date = (new \DateTime('00:00:00'))->sub(new \DateInterval('P4Y'));
      //Pour chaque date de naissance on vérifie qu'elle correspond bien à un enfant de plus de 4ans
      return $this->getBirthDate() < $date;
      //pareil que
      // if ($this->getBirthDate() < $date){
      //   return true;
      // }else{
      //   return false;
      // }

    }


}
