(function ($) {
    $(function () {

        var nombreEvenementsPresents = $('.lienEvenement').length;
        var nombreEvenementsTotal = $('#wrapper-evenement').data('evenement');
        affichageBouton();
        ajouteEvenements();

        function affichageBouton() {
            nombreEvenementsPresents = $('.lienEvenement').length;
            if (nombreEvenementsTotal > nombreEvenementsPresents) {
                if ($('#ajoutePlusEvenements').length === 0) {
                    var bouton = ' <button class="btn btn-default" class="ajoutePlusEvenements" id="ajoutePlusEvenements" >Afficher plus d\'événements</button >';
                    $('#wrapper-evenement').after(bouton);
                }
            } else {
                if ($('#ajoutePlusEvenements').length > 0) {
                    $('#ajoutePlusEvenements').remove();
                }
            }
        }

        function ajouteEvenements(){
            $('#ajoutePlusEvenements').click(function(){
                $.post('recupererPlusEvenements/' + nombreEvenementsPresents, function(data){
                    $(data).appendTo('#wrapper-evenement').hide().fadeIn();
                    nombreEvenementsPresents = $('.lienEvenement').length;
                    affichageBouton();
                })

            })
        }
    })
})(jQuery);
