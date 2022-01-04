<?php
session_start();
include_once('../utils/checkAuth.php');
Auth::Check(false);

include_once('../core/database/db.php');
include_once('../core/vehicle/manager.php');
include_once('../core/abastecimento/manager.php');

$vehiclePlaca = "XXXXXXX";
$abastecimentosJSON = "[]";
if (isset($_GET['create'])) {
  $_SESSION['pageDashboard'] = 'create';
} else if (!empty($_GET['edit'])) {
  $connection = BancoDeDados::Connect();
  $vehiclePlaca = Vehicle::GetById($connection, $_GET['edit']);
  $abastecimentosJSON = Abastecimento::GetALlById($connection, $_GET['edit']);
  $_SESSION['pageDashboard'] = 'edit';
} else if (!empty($_GET['view'])) {
  $connection = BancoDeDados::Connect();
  $vehiclePlaca = Vehicle::GetById($connection, $_GET['view']);
  $abastecimentosJSON = Abastecimento::GetALlById($connection, $_GET['view']);
  $_SESSION['pageDashboard'] = 'view';
} else {
  $_SESSION['pageDashboard'] = 'create';
}

if ($_SESSION['pageDashboard'] != 'create' && $_SESSION['pageDashboard'] != 'view' && $_SESSION['pageDashboard'] != 'edit') {
  $_SESSION['pageDashboard'] = 'create';
}


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vehicle</title>
  <?php
  if ($_SESSION['pageDashboard'] == 'view') {
    echo "<link rel=\"stylesheet\" href=\"/css/dashboardAreas.css\">";
  } else {
    echo "<link rel=\"stylesheet\" href=\"/css/main.css\">";
  }
  ?>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
</head>

<body onload="init()">
  <?php
  if ($_SESSION['pageDashboard'] == 'create') {
  ?>
    <header>
      <a href="/dashboard.php">Voltar</a>
    </header>
    <main class="loginContainer">
      <form action="/api/vehicle.php" method="post" autocomplete="off">
        <img src="/img/icon.png" alt="icon">
        <p>Por favor insira a placa do veículo.</p>
        <input type="text" name="placa" id="placa" placeholder="Placa" minlength="7" maxlength="7" required>
        <input type="submit" value="SALVAR">
      </form>
    </main>
  <?php
  } else if ($_SESSION['pageDashboard'] == 'edit') {
  ?>
    <header>
      <a href="/dashboard.php">Voltar</a>
    </header>
    <main class="loginContainer">
      <form action="/api/vehicle.php" method="post" autocomplete="off">
        <img src="/img/icon.png" alt="icon">
        <p>Por favor insira a nova placa do veículo.</p>
        <input type="hidden" name="id" value="<?php echo $_GET['edit']; ?>">
        <input type="text" name="placa" id="placa" placeholder="Placa" minlength="7" maxlength="7" required>
        <input type="submit" value="SALVAR">
      </form>
    </main>
  <?php
  } else if ($_SESSION['pageDashboard'] == 'view' && $abastecimentosJSON != "[]") {
  ?>
    <header>
      <a href="/dashboard/abastecimento.php?id=<?php echo $_GET['view']; ?>">Novo Abastecimento</a>
      <a href="/dashboard.php">Voltar</a>
    </header>
    <main class="loginContainer">
      <form action="/api/abastecimento.php" method="get" autocomplete="off">
        <input type="hidden" name="id" id="abastecimento_id">
        <input type="hidden" name="active">
        <select id="data" onchange="showAbastecimento()"></select>
        <div id="map" class="map"></div>
        <div class="valores" id="valores"></div>
        <input type="submit" value="DESATIVAR ABASTECIMENTO">
      </form>
    </main>
    <script>
      const abastecimentoId = document.querySelector('#abastecimento_id');
      const select = document.querySelector('#data');
      const map = document.querySelector('#map');
      const valores = document.querySelector('#valores');
      const abastecimentos = <?php echo $abastecimentosJSON; ?>;

      const mapManager = L.map('map');
      L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
        minZoom: 1,
        maxZoom: 20
      }).addTo(mapManager);
      const marker = L.marker([-3.71839, -38.5434]).addTo(mapManager);

      function addValues(km_atual, qtd_litros, valor_por_litro, valor) {
        let titles = ["KM Atual", "QTD. Litros", "VALOR POR LITRO", "TOTAL"];
        let data = [km_atual, qtd_litros, valor_por_litro, valor];

        titles.forEach((value, index, array) => {
          console.log(value)
          let titleElem = document.createElement("p");
          titleElem.innerHTML = value;

          let valueElem = document.createElement("p");
          valueElem.className = "valorContent"
          valueElem.innerHTML = data[index];

          valores.appendChild(titleElem);
          valores.appendChild(valueElem);
        })
      }

      function update() {
        valores.innerHTML = null;
        for (let i = 0; i < abastecimentos.length; i++) {
          if (abastecimentos[i].dt_abastecimento == select.value) {
            abastecimentoId.value = abastecimentos[i].id;
            addValues(abastecimentos[i].km_atual, abastecimentos[i].qtd_litros, abastecimentos[i].valor_por_litro, abastecimentos[i].valor);
            mapManager.setView([abastecimentos[i].latitude, abastecimentos[i].longitude], 15);
            marker.setLatLng([abastecimentos[i].latitude, abastecimentos[i].longitude]);
          }
        }
      }

      function init() {
        abastecimentos.forEach((value) => {
          let optElem = document.createElement("option");
          optElem.innerHTML = value.dt_abastecimento;

          select.appendChild(optElem);
        });
        update();
      }

      function showAbastecimento(e) {
        update();
      }
    </script>
  <?php
  } else if ($_SESSION['pageDashboard'] == 'view' && $abastecimentosJSON == "[]") {
  ?>
    <header>
      <a href="/dashboard/abastecimento.php?id=<?php echo $_GET['view']; ?>">Novo Abastecimento</a>
      <a href="/dashboard.php">Voltar</a>
    </header>
    <div class="contentMain">
      <p class="titleAbs">Sem Abastecimentos</p>
    </div>
  <?php
  }
  ?>
</body>

</html>