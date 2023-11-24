<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css">
    
    <!-- Search CSS Library -->
    <link rel="stylesheet" href="asets/plugins/leaflet-search/leaflet-search.css"/>

    <!-- Geolocation CSS Library for Plugin -->
    <link rel="stylesheet" href="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.css" />

    <!-- Leaflet Mouse Position CSS Library -->
    <link rel="stylesheet" href="asets/plugins/leaflet-mouseposition/L.Control.MousePosition.css"/>

    <!-- Routing CSS Library -->
    <link rel="stylesheet" href="asets/plugins/leaflet-routing/leaflet-routing-machine.css"/>

    <style>
    html, body, #map {
        height: 100%;
        width: 100%;
        margin: 0px;
    }

    /* Style dari judul */
    *.info {
        padding: 6px 8px;
        font: 14px/16px Arial, Helvetica, sans-serif;
        background: white;
        background: rgba(255,255,255,0.8);
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        border-radius: 5px;
        text-align: center;
    }

    .info h2 {
        margin: 0 0 5px;
        color: #777;
    }
    </style>
</head>
<body>
    <script src = "https://code.jquery.com/jquery-3.6.0.min.js"></script>  
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>

    <!-- Search JavaScript Library -->
    <script src="asets/plugins/leaflet-search/leaflet-search.js"></script>

    <!-- Geolocation Javascript Library -->
    <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.min.js"></script>

    <!-- Leaflet Mouse Position JavaScript Library -->
    <script src="asets/plugins/leaflet-mouseposition/L.Control.MousePosition.js"></script>

    <!-- Routing JavaScript Library -->
    <script src="asets/plugins/leaflet-routing/leaflet-routing-machine.js"></script>
    <script src="asets/plugins/leaflet-routing/leaflet-routing-machine.min.js"></script>

    <div id="map"></div>
        <script>
            /* Penambahan peta */
            var map = L.map('map').setView([-6.99, 108.89], 11);
        
            /* Penambahan basemap dari tile layer */
            var basemap1 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | <a href="DIVSIG UGM" target="_blank">DIVSIG UGM</a>'});
        
            var basemap2 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri | <a href="Latihan WebGIS" target="_blank">DIVSIG UGM</a>'});
        
            var basemap3 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri | <a href="Lathan WebGIS" target="_blank">DIVSIG UGM</a>'});
        
            var basemap4 = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
            basemap3.addTo(map);
            
            /* Penambahan poligon dari geoserver */
            var wfsgeoserver2 = L.geoJson(null, {        
                poligonToLayer: function (feature, latlng) {
                return L.polygon(latlng);
            },

            onEachFeature: function (feature, layer) {
                var content = "Kecamatan : " + feature.properties.wadmkc;
                layer.on({
                    click: function (e) {
                        wfsgeoserver2.bindPopup(content);
                    },
                    mouseover: function(e) {
                        wfsgeoserver2.bindTooltip(feature.properties.wadmkc).openTooltip;
                    },
                    mouseout: function(e) {
                        wfsgeoserver2.closePopup();
                    }
                });
            }
        });

    $.getJSON("geoserver.php", function (data) {
        wfsgeoserver2.addData(data);
        map.addLayer(wfsgeoserver2);
    });

    //mengakses database mysql
    <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "latihan";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
        
        //mengakses data pada tabel penduduk
        $sql = "SELECT * FROM penduduk";
        $result = $conn->query($sql);
        
        //menampilkan marker menggunakan data latitude dan longitude
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $lat = $row["latitude"];
                $long = $row["longitude"];
                $info = $row["posyandu"];
                echo "L.marker([$lat, $long]).addTo(map).bindPopup('$info');";
            } 
        }
        else {
            echo "0 results";
        }
            $conn->close();
    ?>

    /* Penambahan Judul */
    var title = new L.Control();
    title.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info');
        this.update();
        return this._div;
    };
    title.update = function () {
        this._div.innerHTML = '<h2>SIGIBU BREBES</h2>Layanan Kesehatan di Kabupaten Brebes'
    };
    title.addTo(map);

    /* Control Layer */
    var baseMaps = {
            "OpenStreetMap": basemap1,
            "Esri World Street": basemap2,
            "Esri Imagery": basemap3, 
            "Stadia Dark Mode": basemap4 
        };
        L.control.layers(baseMaps).addTo(map);
    
    /* Watermark */
    L.Control.Watermark = L.Control.extend({
        onAdd: function(map) {
            var img = L.DomUtil.create('img');
            img.src = 'img/logo/LOGO_SIG_BLUE.png';
            img.style.width = '200px';
            return img;
        }
    });
    L.control.watermark = function(opts) {
        return new L.Control.Watermark(opts);
    }
    L.control.watermark({ position: 'bottomleft' }).addTo(map);

    /* Image Legend */
    L.Control.Legend = L.Control.extend({
        onAdd: function(map) {
            var img = L.DomUtil.create('img');
            img.src = 'img/legend/legenda.jpg';
            img.style.width = '200px';
            return img;
        }
    });
    L.control.Legend = function(opts) {
        return new L.Control.Legend(opts);
    }
    L.control.Legend({ position: 'bottomleft' }).addTo(map);

    /*Plugin Search */
    var searchControl = new L.Control.Search({
        position:"topleft",
        layer: wfsgeoserver2, //Nama variabel layer
        propertyName: 'wadmkc', //Field untuk pencarian
        marker: false,
        moveToLocation: function(latlng, title, map) {
            var zoom = map.getBoundsZoom(latlng.layer.getBounds());
            map.setView(latlng, zoom);
        }
    });

    searchControl.on('search:locationfound', function(e) {
        e.layer.setStyle({
            fillColor: '#ffff00',
            color: '#0000ff'
        });
    }).on('search:collapse', function(e) {
        featuresLayer.eachLayer(function(layer) {
            featuresLayer.resetStyle(layer);
        });
    });
    map.addControl(searchControl);

    /*Plugin Geolocation */
    var locateControl = L.control.locate({
        position: "topleft",
        drawCircle: true,
        follow: true,
        setView: true,
        keepCurrentZoomLevel: false,
        markerStyle: {
            weight: 1,
            opacity: 0.8,
            fillOpacity: 0.8,
        },
        circleStyle: {
            weight: 1,
            clickable: false,
        },
        icon: "fas fa-crosshairs",
        metric: true,
        strings: {
            title: "Click for Your Location",
            popup: "You're here. Accuracy {distance} {unit}",
            outsideMapBoundsMsg: "Not available",
        },
        locateOptions: {
            maxZoom: 16,
            watch: true,
            enableHighAccuracy: true,
            maximumAge: 10000,
            timeout: 10000,
        },
    })
    .addTo(map);

    /*Plugin Mouse Position Coordinate */
    L.control.mousePosition({ position: "bottomright", separator: ",", prefix: "Point Coodinate: " }).addTo(map);

    /* Plugin Routing */
    L.Routing.control({
        waypoints: [
            L.latLng(-6.871909, 109.056416),
            L.latLng(-7.009155, 108.843707)
        ],
        routeWhileDragging: true
    }).addTo(map);
    </script>
</body>
</html>