/**
 * Created by Jérémie on 24/04/2016.
 */


// alert qui s'affiche lorsque j'appuie sur le bouton ajouter un lieu de récup' et qui indique ce qui faut faire
var alertAjout = '<div class="alert alert-info alert-dismissible fade in text-center" id="position-alert" role="alert" style="z-index: 10022;position: relative;top: 0;left: 0; padding: 26px 10px;""> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> <strong>Double clic</strong> sur la carte pour positionner le lieu de récup\' !</div>';


function desactiveLeafletEvents() {
    map.dragging.disable();
    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();
    map.boxZoom.disable();
    map.keyboard.disable();
}


function activeLeafletEvents() {
    map.dragging.enable();
    map.dragging.enable();
    map.touchZoom.enable();
    map.doubleClickZoom.enable();
    map.scrollWheelZoom.enable();
    map.boxZoom.enable();
    map.keyboard.enable();
}


// ajoute une alert lors du clic sur le bouton et active le positionnement du lieu
$('.bouton-ajouter-lieu').click(function () {
    $('#mapid').append(alertAjout);


    map.once('dblclick', passageEtape3);

    // annule l'événement du double clic lors de la fermeture de l'alert
    $('#position-alert').on('closed.bs.alert', function () {
        map.off('dblclick', passageEtape3);
    })

});



// marker pour l'ajout du formulaire ne s'affiche que pour le visuel
var markerVisuel = L.marker();


// capture la position du dbl click et passe à l'étape 3
function passageEtape3(e) {
    // ferme l'alert
    $("#position-alert").alert('close');

    //  récupère la position du curseur et met les informations dans le bon format
    var pos = '[' + e.latlng.lat + ", " + e.latlng.lng + "]"

    // affiche un marker visuel pour aider l'internaute à visualiser où il a positionner le marker
    markerVisuel.setLatLng(e.latlng).addTo(map);
    afficherEtape3(pos);
}

function afficherEtape3(position) {
    $('.popup-ajout').show();

    // rempli la popup avec le contenu du formulaire
    $.ajax("ajoutLieu", {
        beforeSend: function () {
            var $imageLoading = '<img alt="chargement" src="../css/images/loading.gif" style="position:absolute;left:50%;top:50%;"/>'
            $('.popup-ajout-emplacement').html($imageLoading);
        },
        success: function (data) {
            $('.popup-ajout-emplacement').html(data);
            $('#form_lieu_localisation').val(position);
            initBoutonAJouter();
        }
    })

    //Supprime le marker si il quitte la popup
    $('.popup-ajout-fermer').click(function () {
        map.removeLayer(markerVisuel);
    })
}


// Initialise le bouton en boucle pour qui puisse traiter le formulaire en ajax
function initBoutonAJouter() {
    $('#ajouter-lieu').click(function () {

        $.ajax("ajoutLieu", {
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            type: 'POST',
            data: $('#formulaire-ajout').serialize(),
            beforeSend: function () {
                var $imageLoading = '<img alt="chargement" src="../css/images/loading.gif" style="position:absolute;left:50%;top:50%;"/>'
                $('.popup-ajout-emplacement').html($imageLoading);
            },
            success: function (data) {
                $('.popup-ajout-emplacement').html(data);
                initBoutonAJouter();
                afficheMarker();
            }
        })
    })
}
