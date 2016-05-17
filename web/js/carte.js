/**
 * Created by Jérémie on 24/04/2016.
 * Permet d'initialiser la map
 * Les variables sont en local
 */


// Importation des layouts et ajout du module layout

var normal = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
    maxZoom: 18
});

var transport = L.tileLayer('https://{s}.tile.thunderforest.com/transport/{z}/{x}/{y}.png', {
    attribution: '&copy; Thunderforest.com. Data &copy; CC-BY-SA OpenStreetMap contributors',
    maxZoom: 18
});

var velo = L.tileLayer('https://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}.png', {
    attribution: '&copy; Thunderforest.com. Data &copy; CC-BY-SA OpenStreetMap contributors',
    maxZoom: 18
});

mapLink =
    '<a href="http://www.esri.com/">Esri</a>';
wholink =
    'i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community';
var aerien = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: '&copy; ' + mapLink + ', ' + wholink,
    maxZoom: 18
});

var map = L.map('mapid', {
    center: [50.629, 3.065],
    zoom: 13,
    layers: normal
});

var baseMaps = {
    "transport": transport,
    "vélo": velo,
    "aerien": aerien,
    "normal": normal
};


// Initialisation des layers

L.control.layers(baseMaps).addTo(map);

// Tableau des markers qui va me servir pour les stocker et les supprimer lors de la recherche

var tabMarkers = [];

