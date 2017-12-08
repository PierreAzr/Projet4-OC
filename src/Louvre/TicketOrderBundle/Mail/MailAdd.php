<?php

namespace Louvre\TicketOrderBundle\Mail;

use Louvre\TicketOrderBundle\Event\SendMailEvent;
use Louvre\TicketOrderBundle\Entity\Billet;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\RequestStack;

class MailAdd
{

protected $mailer;
protected $twig;

  public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
  {
    $this->mailer = $mailer;
    $this->twig = $twig;
  }

  // Méthode pour envoyer l'email de confirmation contenant les billets
  public function SendMailAct(SendMailEvent $event)
  {

    // on récupére la mise en forme de l'email
    $email = $this->twig->render(
                'LouvreTicketOrderBundle:Email:email.html.twig',
                array('ticketOrder' => $event->getTicketOrder()
                ));

    //nom du fichier pdf
    $ref = $event->getTicketOrder()->getId();
    $namepdf = "Louvre.Billet.Commande.N$ref.pdf";

    // on récupére la mise en forme de la piece jointe
    $pdf = $this->twig->render(
          'LouvreTicketOrderBundle:Email:emailhtml2pdf.html.twig',
              array(
                  'ticketOrder' => $event->getTicketOrder()
          ));

    // on récupere la mise en forme de l'email
    $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
    $html2pdf->pdf->SetDisplayMode('real');
    $html2pdf->writeHTML($pdf);
    $html2pdf->output('pdf/'.$namepdf, 'F');

    // on récupere l'email du destinataire
    $emailOrder = $event->getTicketOrder()->getEmail();

    $message =  \Swift_Message::newInstance('Musée du louvre')
      ->setTo($emailOrder)
      ->setFrom('louvre@exemple.com', 'Musée du Louvre')
      ->setBody($email, 'text/html')
      ->attach(\Swift_Attachment::fromPath(__DIR__ . "/../../../../web/pdf/$namepdf"))
      ;


      $this->mailer->send($message);


  }

}


 ?>
