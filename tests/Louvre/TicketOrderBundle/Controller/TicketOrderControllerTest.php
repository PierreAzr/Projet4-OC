<?php

namespace Louvre\TicketOrderBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TicketOrderControllerTest extends WebTestCase {

  // Test qu'on arrive bien sur la page accueil
  public function testHomepageIsUp() {

      $client = static::createClient();
      $crawler = $client->request('GET', '/');
      $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
  }

  // Test quand le formulaire est correctement rempli
  public function testvalidOrder() {

      $client = static::createClient();
      // requête de la route /
      $crawler = $client->request('GET', '/');
      // on test le succées de la requête
      $this->assertEquals(200, $client->getResponse()->getStatusCode());

      // on sélectione sur la page le bouton de validation du formulaire et on le soumet
      $form = $crawler->selectButton('louvre_ticketorderbundle_ticketorder[Valider]')->form();

      // on récupére la valeur du token en cours
      $token = $form['louvre_ticketorderbundle_ticketorder[_token]']->getValue();
      // on crée un billet contenant la valeur du token avec la fonction dataOrder
      $data = $this->dataOrder($token);
      // requête de la route / avec les donnée envoyé par la méthode POST
      $client->request('POST', '/', $data);
      // on test le succées de la redirection
      $this->assertSame(302, $client->getResponse()->getStatusCode());

  }

  public function testTarif() {

       // *** tarif enfant ***
       $client = static::createClient();
       $crawler = $client->request('GET', '/');
       $this->assertEquals(200, $client->getResponse()->getStatusCode());

       $form = $crawler->selectButton('louvre_ticketorderbundle_ticketorder[Valider]')->form();
       $token = $form['louvre_ticketorderbundle_ticketorder[_token]']->getValue();
       $data = $this->dataOrder($token);
       // on modifie la valeur de l'année de naissance pour la faire à celle du tarif enfant
       $data['louvre_ticketorderbundle_ticketorder']['tickets'][0]['birthDate']['year'] = '2012';

       $client->request('POST', '/', $data);
       $this->assertSame(302, $client->getResponse()->getStatusCode());
       // on fait la redirection
       $crawler = $client->followRedirect();
       // on test que le tarif calculer correspond au tarif voulu en recherchant le nom du tarif afficher sur la page
       $this->assertCount(1, $crawler->filter('html:contains("Tarif : Enfant")'));

       // *** tarif senior ***
       $client = static::createClient();
       $crawler = $client->request('GET', '/');
       $this->assertEquals(200, $client->getResponse()->getStatusCode());

       $form = $crawler->selectButton('louvre_ticketorderbundle_ticketorder[Valider]')->form();
       $token = $form['louvre_ticketorderbundle_ticketorder[_token]']->getValue();
       $data = $this->dataOrder($token);
       $data['louvre_ticketorderbundle_ticketorder']['tickets'][0]['birthDate']['year'] = '1950';

       $client->request('POST', '/', $data);
       $this->assertSame(302, $client->getResponse()->getStatusCode());
       $crawler = $client->followRedirect();
       $this->assertCount(1, $crawler->filter('html:contains("Tarif : Senior")'));

       // *** tarif normal ***
       $client = static::createClient();
       $crawler = $client->request('GET', '/');
       $this->assertEquals(200, $client->getResponse()->getStatusCode());

       $form = $crawler->selectButton('louvre_ticketorderbundle_ticketorder[Valider]')->form();
       $token = $form['louvre_ticketorderbundle_ticketorder[_token]']->getValue();
       $data = $this->dataOrder($token);
       $data['louvre_ticketorderbundle_ticketorder']['tickets'][0]['birthDate']['year'] = '1986';

       $client->request('POST', '/', $data);
       $this->assertSame(302, $client->getResponse()->getStatusCode());
       $crawler = $client->followRedirect();
       $this->assertCount(1, $crawler->filter('html:contains("Tarif : Normal")'));

       // *** tarif reduit ***
       $client = static::createClient();
       $crawler = $client->request('GET', '/');
       $this->assertEquals(200, $client->getResponse()->getStatusCode());

       $form = $crawler->selectButton('louvre_ticketorderbundle_ticketorder[Valider]')->form();
       $token = $form['louvre_ticketorderbundle_ticketorder[_token]']->getValue();
       $data = $this->dataOrder($token);
       $data['louvre_ticketorderbundle_ticketorder']['tickets'][0]['priceList'] = 'true';

       $client->request('POST', '/', $data);
       $this->assertSame(302, $client->getResponse()->getStatusCode());
       $crawler = $client->followRedirect();
       $this->assertCount(1, $crawler->filter('html:contains("Tarif : Reduit")'));
  }

  // test si il y a plus de 1000 billets pour une journée
  public function testLimiTickets() {

        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('louvre_ticketorderbundle_ticketorder[Valider]')->form();

        $data = ['louvre_ticketorderbundle_ticketorder' => array(
        "typeTicket"=> "Journée",
        "email"=> "email@mail.com",
        "visitDate"=> "16/11/2018",
        "tickets"=> '',
          "_token"=> $form['louvre_ticketorderbundle_ticketorder[_token]']->getValue(),
        )];

        $oneTicket  =  [
               "name"=> "nom",
               "firstName"=> "prenom",
               "birthDate"=> ["day"=> "5", "month"=> "5","year"=> "1985"],
               "country"=> "FR" ];


        // Pour un billet ça marche
        $data['louvre_ticketorderbundle_ticketorder']['tickets'] = [0 => $oneTicket ];
        $client->request('POST', '/', $data);
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        // On crée mille billets pour le même jour
        $Tickets1001 = [];
        for($i = 0; $i < 1000; $i++) {
            $Tickets1001 = $Tickets1001 + [$i => [$oneTicket]];
        }

        // Pour mille billets commandée le même jour on est pas redirigée
        $data['louvre_ticketorderbundle_ticketorder']['tickets'] = $Tickets1001 ;
        $client->request('POST', '/', $data);
        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }

   public function testError() {

         $client = static::createClient();
         $crawler = $client->request('GET', '/');
         $this->assertEquals(200, $client->getResponse()->getStatusCode());
         $form = $crawler->selectButton('louvre_ticketorderbundle_ticketorder[Valider]')->form();
         $token = $form['louvre_ticketorderbundle_ticketorder[_token]']->getValue();

         //sans erreur
         $data = $this->dataOrder($token);
         $client->request('POST', '/', $data);
         $this->assertSame(302, $client->getResponse()->getStatusCode());

         //erreur date de visite passée
         $data = $this->dataOrder($token);
         $data['louvre_ticketorderbundle_ticketorder']['visitDate'] = '01/01/1900';
         $client->request('POST', '/', $data);
         $this->assertSame(200, $client->getResponse()->getStatusCode());

         //erreur date de visite jour férié
         $data = $this->dataOrder($token);
         $data['louvre_ticketorderbundle_ticketorder']['visitDate'] = '01/05/2020';
         $client->request('POST', '/', $data);
         $this->assertSame(200, $client->getResponse()->getStatusCode());

         //erreur email erronée
         $data = $this->dataOrder($token);
         $data['louvre_ticketorderbundle_ticketorder']['email'] = 'exemple';
         $client->request('POST', '/', $data);
         $this->assertSame(200, $client->getResponse()->getStatusCode());

         //erreur email vide
         $data = $this->dataOrder($token);
         $data['louvre_ticketorderbundle_ticketorder']['email'] = '';
         $client->request('POST', '/', $data);
         $this->assertSame(200, $client->getResponse()->getStatusCode());

         //erreur pas de billet
         $data = $this->dataOrder($token);
         $data['louvre_ticketorderbundle_ticketorder']['tickets']= '';
         $client->request('POST', '/', $data);
         $this->assertSame(200, $client->getResponse()->getStatusCode());

         //erreur nom 2 caractere minimum
         $data = $this->dataOrder($token);
         $data['louvre_ticketorderbundle_ticketorder']['tickets'][0]['name'] = 'g';
         $client->request('POST', '/', $data);
         $this->assertSame(200, $client->getResponse()->getStatusCode());

         //erreur nom vide
         $data = $this->dataOrder($token);
         $data['louvre_ticketorderbundle_ticketorder']['tickets'][0]['name'] = '';
         $client->request('POST', '/', $data);
         $this->assertSame(200, $client->getResponse()->getStatusCode());

         //erreur prénom 2 caractere minimum
         $data = $this->dataOrder($token);
         $data['louvre_ticketorderbundle_ticketorder']['tickets'][0]['firstName'] = 'g';
         $client->request('POST', '/', $data);
         $this->assertSame(200, $client->getResponse()->getStatusCode());

         //erreur prénom vide
         $data = $this->dataOrder($token);
         $data['louvre_ticketorderbundle_ticketorder']['tickets'][0]['firstName'] = '';
         $client->request('POST', '/', $data);
         $this->assertSame(200, $client->getResponse()->getStatusCode());

         //erreur date de naissance dépasse la date de visite
         $data = $this->dataOrder($token);
         $data['louvre_ticketorderbundle_ticketorder']['tickets'][0]['birthDate']['year'] = '2020';
         $client->request('POST', '/', $data);
         $this->assertSame(200, $client->getResponse()->getStatusCode());

    }

    // Fonction qui crée un billet
    public function dataOrder($token)
    {
        return
            ['louvre_ticketorderbundle_ticketorder' => array(
               "typeTicket"=> "Journée",
               "email"=> "email@mail.com",
               "visitDate"=> "16/11/2018",
               "tickets"=> [0 => [
                 'priceList' => false,
                  "name"=> "nom",
                  "firstName"=> "prenom",
                  "birthDate"=> ["day"=> "5", "month"=> "5","year"=> "1985"],
                  "country"=> "FR" ]],
                 "_token"=> $token
               )];

    }


}
