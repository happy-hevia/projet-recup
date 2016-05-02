/**
 * Created by Jérémie on 24/04/2016.
 */







// permet d'afficher les markers lors du premier chargement
afficheMarker();

// affiche le marker lors de la modification du formulaire
$('#formulaire-recherche').change(function () {
    afficheMarker();
})

//affiche les markers selon les paramètres de la recherche personnalisée
function afficheMarker() {
    $.ajax("afficheMarker", {
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        type: 'get',
        dataType: 'script',
        data: {
            note: $('.recherche-note').val(),
            supermarche: $('#form_supermarche').is(':checked'),
            epicerie: $('#form_epicerie').is(':checked'),
            boulangerie: $('#form_boulangerie').is(':checked'),
            marche: $('#form_marche').is(':checked'),
            autre: $('#form_autre').is(':checked'),
            facile: $('#form_facile').is(':checked'),
            difficile: $('#form_difficile').is(':checked'),
            jour: $('#form_jour').val()
        },
    });
}

