<?php
session_start();
include_once('./utils/checkAuth.php');
Auth::Check(false);

include_once('./core/database/db.php');
include_once('./core/vehicle/manager.php');

$connection = BancoDeDados::Connect();

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="/css/dashboard.css">
</head>

<body>
  <header>
    <a href="/dashboard/vehicle.php?create">Adicionar Veículo</a>
    <a href="/dashboard/conta.php">Conta</a>
    <a href="/auth/signout.php">Sair</a>
  </header>
  <main class="vehiclesContainer">
    <?php
    echo Vehicle::GetAll($connection);
    ?>
  </main>
</body>

</html>