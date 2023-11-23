<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kab. Sleman</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css">
    
    <!-- Search CSS Library -->
    <link rel="stylesheet" href="plugins/leaflet-search/leaflet-search.css"/>

    <!-- Geolocation CSS Library for Plugin -->
    <link rel="stylesheet" href="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.css" />

    <!-- Leaflet Mouse Position CSS Library -->
    <link rel="stylesheet" href="plugins/leaflet-mouseposition/L.Control.MousePosition.css"/>

    <!-- Leaflet Measure CSS Library -->
    <link rel="stylesheet" href="plugins/leaflet-measure/leaflet-measure.css"/>

    <!-- EasyPrint CSS Library -->
    <link rel="stylesheet" href="plugins/leaflet-easyprint/easyPrint.css"/>

    <!-- Routing CSS Library -->
    <link rel="stylesheet" href="plugins/leaflet-routing/leaflet-routing-machine.css"/>

    <!-- MarkerCluster CSS Library -->
    <link rel="stylesheet" href="plugins/leaflet-markercluster/MarkerCluster.css"/>
    <link rel="stylesheet" href="plugins/leaflet-markercluster/MarkerCluster.Default.css"/>

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
    <script src="plugins/leaflet-search/leaflet-search.js"></script>

    <!-- Geolocation Javascript Library -->
    <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.min.js"></script>

    <!-- Leaflet Mouse Position JavaScript Library -->
    <script src="plugins/leaflet-mouseposition/L.Control.MousePosition.js"></script>

    <!-- Leaflet Measure JavaScript Library -->
    <script src="plugins/leaflet-measure/leaflet-measure.js"></script>

    <!-- EasyPrint JavaScript Library -->
    <script src="plugins/leaflet-easyprint/leaflet.easyPrint.js"></script>

    <!-- Routing JavaScript Library -->
    <script src="plugins/leaflet-routing/leaflet-routing-machine.js"></script>
    <script src="plugins/leaflet-routing/leaflet-routing-machine.min.js"></script>

    <!-- Markercluster JavaScript Library -->
    <script src="plugins/leaflet-markercluster/leaflet.markercluster.js"></script>
    <script src="plugins/leaflet-markercluster/leaflet.markercluster-src.js"></script>

    <div id="map"></div>

    <script>
    /* Penambahan peta */
    var map = L.map('map').setView([-7.77, 110.37], 12);

    /* Penambahan basemap dari tile layer */
    var basemap1 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | <a href="DIVSIG UGM" target="_blank">DIVSIG UGM</a>'});
        
    var basemap2 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri | <a href="Latihan WebGIS" target="_blank">DIVSIG UGM</a>'});
        
    var basemap3 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri | <a href="Lathan WebGIS" target="_blank">DIVSIG UGM</a>'});
        
    var basemap4 = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
    basemap1.addTo(map);

    /* Penambahan marker */
    var marker1 = L.marker([-7.7709, 110.3763]);
    marker1.addTo(map);
    marker1.bindPopup("Universitas Gadjah Mada");
    
    /* Penambahan poligon dari geoserver */
    var wfsgeoserver2 = L.geoJson(null, {        
        poligonToLayer: function (feature, latlng) {
        return L.polygon(latlng);
    },

    onEachFeature: function (feature, layer) {
        var content = "Kecamatan : " + feature.properties.kecamatan + "<br>" + "jumlah penduduk: " + feature.properties.jumlah + " jiwa";
            layer.on({
                click: function (e) {
                    wfsgeoserver2.bindPopup(content);
                },
                mouseover: function(e) {
                    wfsgeoserver2.bindTooltip(feature.properties.kecamatan).openTooltip;
                },
                mouseout: function(e) {
                    wfsgeoserver2.closePopup();
                }
            });
        }
    });

    $.getJSON("/pgweb-acara9/geoserver_poligon.php", function (data) {
        wfsgeoserver2.addData(data);
        map.addLayer(wfsgeoserver2);
    });

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
                $info = $row["kecamatan"];
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
        this._div.innerHTML = '<h2>LATIHAN WEBGIS | PETA KABUPATEN SLEMAN</h2>MATAKULIAH PEMROGRAMAN GEOSPASIAL : WEB'
    };
    title.addTo(map);

    /* Penambahan Control Layer */
    var baseMaps = {
            "OpenStreetMap": basemap1,
            "Esri World Street": basemap2,
            "Esri Imagery": basemap3, 
            "Stadia Dark Mode": basemap4 
        };
    
    var overlayMaps = {
        "Universitas Gadjah Mada": marker1
    };
    L.control.layers(baseMaps, overlayMaps, 
    {collapsed: false}).addTo(map);

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
        propertyName: 'kecamatan', //Field untuk pencarian
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

    /*Plugin Measurement Tool */
    var measureControl = new L.Control.Measure({
        position: "topleft",
        primaryLengthUnit: "meters",
        secondaryLengthUnit: "kilometers",
        primaryAreaUnit: "hectares",
        secondaryAreaUnit: "sqmeters",
        activeColor: "#FF0000",
        completedColor: "#00FF00",
    });
    measureControl.addTo(map);

    /*Plugin EasyPrint */
    L.easyPrint({
        title: "Print",
    }).addTo(map);

    /* Plugin Routing */
    L.Routing.control({
        waypoints: [
            L.latLng(-7.6567694, 110.4036224),
            L.latLng(-7.6822027, 110.4299636)
        ],
        routeWhileDragging: true
    }).addTo(map);

    /* Plugin Marker Cluster*/
    var addressPoints = [
        [-7.6032877, 110.415879, "Tlogo Putri Kaliurang"],
        [-7.6128695, 110.4039949,  "Suraloka Interactive Zoo"],
        [-7.6054853, 110.4196926, "The Lost World Castle"],
        [-7.6129574, 110.401955, "Museum Gunungapi Merapi"]
    ]
    var markers = L.markerClusterGroup();
    for (var i = 0; i < addressPoints.length; i++){
        var a = addressPoints[i];
        var title1 = a[2];
        var marker = L.marker(new L.LatLng(a[0], a[1]),{
            title: title1
        });
        
        marker.bindPopup(title);
        markers.addLayer(marker);
    }
    map.addLayer(markers);

    var addressPoints2 = [
        [-7.7214532, 110.331134, "Kampung Flory Jogja"],
        [-7.7214532, 110.331134,  "Mini Zoo Jogja Exotarium"],
        [-7.7160949, 110.3405754, "Sleman City Hall"],
        [-7.7250254, 110.3168003, "Omah Kecebong"],
        [-7.7139313, 110.2970203, "Kolam Renang Tirta Anggita"]
    ]
    var markers = L.markerClusterGroup();
    for (var i = 0; i < addressPoints2.length; i++){
        var a = addressPoints2[i];
        var title1 = a[2];
        var marker = L.marker(new L.LatLng(a[0], a[1]),{
            title: title1
        });
        
        marker.bindPopup(title);
        markers.addLayer(marker);
    }
    map.addLayer(markers);

    </script>
</body>
</html>