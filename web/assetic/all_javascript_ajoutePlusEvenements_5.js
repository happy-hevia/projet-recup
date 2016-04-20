(function ($) {
    $(function () {

        var nombreEvenementsPresents = $('.lienEvenement').length;
        var nombreEvenementsTotal = $('#wrapper-evenement').data('evenement');

        affichageBouton();

        //affiche le bouton d'ajout d'événements si tous les événements ne sont pas ajoutés sinon le supprime si il existe

        function affichageBouton() {

            var ajoutePlusEvenements = $('#ajoutePlusEvenements');

            if (nombreEvenementsTotal > nombreEvenementsPresents) {
                if (ajoutePlusEvenements.length === 0) {
                    var bouton = ' <button class="btn btn-default ajoutePlusEvenements" id="ajoutePlusEvenements" >Afficher plus d\'événements</button >';
                    $('#wrapper-evenement').after(bouton);
                    ajouteEvenements();
                }
            } else {
                if (ajoutePlusEvenements.length > 0) {
                    ajoutePlusEvenements.remove();
                }
            }
        }

        //ajoute l'événement appel ajax sur le bouton
        function ajouteEvenements(){
            $('#ajoutePlusEvenements').click(function(){
                $(this).remove();
                $.post('recupererPlusEvenements/' + nombreEvenementsPresents, function(data){
                    $(data).appendTo('#wrapper-evenement').hide().fadeIn();
                    nombreEvenementsPresents = $('.lienEvenement').length;
                    affichageBouton();
                })

            })
        }
    })
})(jQuery);
