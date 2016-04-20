(function ($) {
    $(function () {

        afficherFormulaire();
        cacherFormulaire();
        affichageActualisation();


        function afficherFormulaire() {
            $('.button-or').click(function () {
                var $formulaire = $('#formulaire-contact');
                var $formObjet = $('#form_objet');

                //Apparition en fondu
                $formulaire.hide();
                $formulaire.fadeIn("fast");

                //focus du formulaire
                $('#form_email').focus();


                //Ajoute l'objet selon le bouton appuyé
                var tableauObjet = [];
                $('.button-or').each(function(){
                    tableauObjet.push($(this).data('objet'));
                })
                if ($formObjet.val() == "" || tableauObjet.indexOf($formObjet.val()) != -1 ) {
                    $formObjet.val($(this).data('objet'));
                }


                // smooth scroll vers le formulaire
                var speed = 755; // Durée de l'animation (en ms)
                $('body, html').animate({scrollTop: $($formulaire).offset().top}, speed); // Go

            })
        }

        function cacherFormulaire() {
            $('#annuler-contact').click(function () {
                $('#formulaire-contact').fadeOut();
            });
        }

        //Affiche le formulaire seulement si il y a des erreurs sinon n'affiche rien
        function affichageActualisation(){
            if($('.form-erreur').children().length > 0){
                $('#formulaire-contact').show();
            }
        }
    })
})(jQuery);
