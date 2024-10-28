<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>WebGIS Galuh</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
		<style>
			#map {
            width: 100%;
            height: 600px;
        }
        #container {
            display: flex;
        }
        #table-container {
            width: 50%;
            padding: 10px;
            overflow-y: auto;
            height: 600px;
        }
        #map-container {
            width: 50%;
        }
		</style>
	</head>
	<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <nav class="navbar" style="background-color: #f1d2e2;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="icon/map.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
                WebGIS Galuh
            </a>
            <a class="btn btn-success" href="http://localhost/pgweb8/index.html" role="button">Input</a>
        </div>
    </nav>

    <div id="container">
        <!-- Bagian Tabel -->
        <div id="table-container">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Kecamatan</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Luas</th>
                        <th>Jumlah Penduduk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $conn = new mysqli("localhost", "root", "", "latihan");
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM penduduk";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["kecamatan"] . "</td>";
                                echo "<td>" . $row["latitude"] . "</td>";
                                echo "<td>" . $row["longitude"] . "</td>";
                                echo "<td>" . $row["luas"] . "</td>";
                                echo "<td>" . $row["jumlah_penduduk"] . "</td>";
                                echo "<td>
                                    <form method='post' action='delete.php' style='display:inline-block;'>
                                        <input type='hidden' name='id' value='" . $row["id"] . "' />
                                        <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                                    </form>
                                    <form method='post' action='update.php' style='display:inline-block;'>
                                        <input type='hidden' name='id' value='" . $row["id"] . "' />
                                        <button type='submit' class='btn btn-primary btn-sm'>Update</button>
                                    </form>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>0 results</td></tr>";
                        }
                        $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        <div id="map-container">
            <div id="map"></div>
        </div>
    </div>

		<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
		<script>
			// Inisialisasi peta
			var map = L.map("map").setView([-7.1363230,108.8470459], 7);

			// Tile Layer Base Map
            var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution:
                            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map);

            var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            });
            var rupabumiindonesia = L.tileLayer('https://geoservices.big.go.id/rbi/rest/services/BASEMAP/Rupabumi_Indonesia/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Badan Informasi Geospasial'
            });


            <?php
        // Create connection
        $conn = new mysqli("localhost", "root", "", "latihan");
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM penduduk";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $long = $row["longitude"];
                $lat = $row["latitude"];
                $info = $row["kecamatan"];
                echo "L.marker([$lat, $long]).addTo(map).bindPopup('$info');";
            }
        } else {
            echo "0 results";
        }

        $conn->close();
    ?>

            // Control Layer
            var baseMaps = {
                "OpenStreetMap": osm,
                "Esri World Imagery": Esri_WorldImagery,
                "Rupa Bumi Indonesia": rupabumiindonesia,
            };

            L.control.layers(baseMaps).addTo(map);

            // Scale
            var scale = L.control.scale(
                {
                position: "bottomright",
                imperial: false,
            });
            scale.addTo(map);

		</script>
	</body>
</html>