<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
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

            var wfsgeoserver2 = L.geoJson(null, {        
    style: function (feature) {
        var giziBuruk = feature.properties.giziburuk; 

        // Menentukan kelas untuk simbologi berdasarkan nilai gizi buruk
        var fillColor;
        if (giziBuruk > 34) {
            fillColor = 'red'; 
        } else if (giziBuruk < 18) {
            fillColor = 'yellow'; 
        } else {
            fillColor = 'orange'; 
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
        var content = "Kecamatan : " + feature.properties.wadmkc + "<br>" + "Kasus Gizi Buruk : " + feature.properties.giziburuk;
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
        this._div.innerHTML = '<h3>SIGIBU BREBES</h3>Sistem Informasi Gizi Buruk Anak Kab. Brebes'
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
            img.src = 'img/legend/legenda2.jpg';
            img.style.width = '200px';
            return img;
        }
    });
    L.control.Legend = function(opts) {
        return new L.Control.Legend(opts);
    }
    L.control.Legend({ position: 'bottomleft' }).addTo(map);

    </script>
</body>
</html>