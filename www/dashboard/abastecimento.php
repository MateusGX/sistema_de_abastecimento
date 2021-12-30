<?php
session_start();
include_once('../utils/checkAuth.php');
Auth::Check(false);

include_once('../core/database/db.php');
include_once('../core/vehicle/manager.php');

$vehiclePlaca = "XXXXXXX";

$connection = BancoDeDados::Connect();
$vehiclePlaca = Vehicle::GetById($connection, $_GET['id']);

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Abastecimento</title>
  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
</head>

<body>
  <main class="loginContainer">
    <form action="/api/abastecimento.php" method="post" autocomplete="off">
      <div class="placa"><?php
                          echo $vehiclePlaca;
                          ?></div>
      <div id="map" class="map"></div>
      <input type="hidden" name="id_veiculo" id="id_veiculo" value="<?php echo $_GET['id']; ?>">
      <input type="hidden" name="latitude" id="latitude">
      <input type="hidden" name="longitude" id="longitude">
      <input type="number" name="km_atual" placeholder="KM Atual" require>
      <input type="number" name="qtd_litros" placeholder="QTD. Litros" require>
      <input type="number" name="valor_por_litro" placeholder="Valor por litro" require>
      <input type="number" name="valor" placeholder="TOTAL" require>
      <input type="submit" value="SALVAR">
      <p><a href="/dashboard/vehicle.php?view=<?php echo $_GET['id']; ?>">Voltar</a></p>
    </form>
  </main>
  <script>
    const latitude = document.querySelector('#latitude');
    const longitude = document.querySelector('#longitude');

    latitude.value = -3.71839;
    longitude.value = -38.5434;

    const map = L.map('map').setView([-3.71839, -38.5434], 13);
    L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
      minZoom: 1,
      maxZoom: 20
    }).addTo(map);

    const marker = L.marker([-3.71839, -38.5434]).addTo(map);
    map.on('dblclick', (e) => {
      marker.setLatLng(e.latlng);
      latitude.value = e.latlng.lat;
      longitude.value = e.latlng.lng;
    })
  </script>
</body>

</html>