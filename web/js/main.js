$(document).ready(function() {

  // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
  var $container = $('div#collectionContainer');

  // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
  var $billet = $('a#appendTicket');
  $billet.click(function(e) {
    addBillet($container);
    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
    return false;
  });

  // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
  var index = $container.find(':input').length;

  // On ajoute un premier champ automatiquement.
  if (index == 0) {
    addBillet($container);
  } else {
    // On ajoute un lien de suppression
    $container.children('div').each(function() {
      addDeleteLink($(this));
    });
  }

  // La fonction qui ajoute un formulaire Billet
  function addBillet($container) {
    // Dans le contenu de l'attribut « data-prototype », on remplace :
    // - le texte "__name__label__" qu'il contient par le label du champ
    // - le texte "__name__" qu'il contient par le numéro du champ
    var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, index+1)
        .replace(/__name__/g, index));

    // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
    addDeleteLink($prototype);

    // On ajoute le prototype modifié à la fin de la balise <div>
    $container.append($prototype);

    // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
    index++;
  }

  // La fonction qui ajoute un lien de suppression d'une catégorie
 function addDeleteLink($prototype) {
   // Création du lien
   $deleteLink = $('<div class="col-sm-12"><div class="buttonticket"><a href="#" class="btn btn-danger">Supprimer le billet</a></div><div style="text-align=center"><hr align="center"></div></div>');
   // Ajout du lien
   $prototype.append($deleteLink);
   // Ajout du listener sur le clic du lien
   $deleteLink.click(function(e) {
     $prototype.remove();
     e.preventDefault(); // évite qu'un # apparaisse dans l'URL
     return false;
   });

 }

 var year = new Date().getFullYear()

//mise en place de datapicker
$("#louvre_ticketorderbundle_ticketorder_visitDate").datepicker({
format: "dd/mm/yyyy",
startDate: "today",
language: "fr",
daysOfWeekDisabled: '2',
datesDisabled: ['01/05/' + year,'01/11/' + year,'25/12/' + year,],
weekStart: 1,
});

//pour avoir le bon format de date 01/01/2000
var fullDate = new Date();
var twoDigitMonth = (fullDate.getMonth() + 1);if(twoDigitMonth.length==1)	twoDigitMonth="0" +twoDigitMonth;
var twoDigitDate = fullDate.getDate()+"";if(twoDigitDate.length==1)	twoDigitDate="0" +twoDigitDate;
var currentDate = twoDigitDate + "/" + twoDigitMonth + "/" + fullDate.getFullYear();
var currentTime = fullDate.getHours();
// //Pour les tests
// var currentTime = 14;

//Fonction pour la selection automatique du type de billet selon l'heure
//à chaque changement de date on vérifie le jour est l'heure
$('#louvre_ticketorderbundle_ticketorder_visitDate').change(function() {
   //si on est le jour même et qu'il est plus de 14h le type de billets est obligatoirement Demi-journée
   if ($('#louvre_ticketorderbundle_ticketorder_visitDate').val() == currentDate && currentTime >= 14) {
       $('#louvre_ticketorderbundle_ticketorder_typeTicket option[value="Demi-journée"]').prop('selected', true);
       $('#louvre_ticketorderbundle_ticketorder_typeTicket option[value="Journée"]').prop('disabled', true);
    }else{
        $('#louvre_ticketorderbundle_ticketorder_typeTicket option[value="Demi-journée"]').prop('selected', false);
        $('#louvre_ticketorderbundle_ticketorder_typeTicket option[value="Journée"]').prop('disabled', false).prop('selected', true);
    }
});

});
