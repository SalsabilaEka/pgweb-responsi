<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css">

    <style>
    html, body, #map {
        height: 100%;
        width: 100%;
        margin: 0px;
    }
    </style>
</head>
<body>
    <script src = "https://code.jquery.com/jquery-3.6.0.min.js"></script>  
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>

    <div id="map"></div>
        <script>
            /* Penambahan peta */
            var map = L.map('map').setView([-6.99, 108.89], 10);
        
            /* Penambahan basemap dari tile layer */
    
            var basemap3 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri | <a href="Lathan WebGIS" target="_blank">DIVSIG UGM</a>'});
            basemap3.addTo(map);

            /* Penambahan poligon dari geoserver */

            var wfsgeoserver2 = L.geoJson(null, {        
    style: function (feature) {
        var giziBuruk = feature.properties.giziburuk; // Asumsikan atribut gizi buruk ada di properti 'giziburuk'

        // Tentukan kriteria untuk simbologi berdasarkan nilai gizi buruk
        var fillColor;
        if (giziBuruk > 34) {
            fillColor = 'red'; // Simbol untuk nilai gizi buruk > 75
        } else if (giziBuruk < 18) {
            fillColor = 'yellow'; // Simbol untuk nilai gizi buruk > 50
        } else {
            fillColor = 'orange'; // Simbol untuk nilai gizi buruk <= 50
        }

        return {
            fillColor: fillColor,
            weight: 2,
            opacity: 1,
            color: "white",
            dashArray: "3",
            fillOpacity: 0.7,
        };
    },

    poligonToLayer: function (feature, latlng) {
        return L.polygon(latlng);
    },

    onEachFeature: function (feature, layer) {
        var content = "Kecamatan : " + feature.properties.wadmkc;
        layer.on({
            click: function (e) {
                wfsgeoserver2.bindPopup(content).openPopup();
            },
            mouseover: function(e) {
                wfsgeoserver2.bindTooltip(feature.properties.wadmkc).openTooltip();
            },
            mouseout: function(e) {
                wfsgeoserver2.closePopup();
                wfsgeoserver2.closeTooltip();
            }
        });
    }
});

$.getJSON("geoserver.php", function (data) {
    wfsgeoserver2.addData(data);
    map.addLayer(wfsgeoserver2);
});

    
    </script>
</body>
</html>