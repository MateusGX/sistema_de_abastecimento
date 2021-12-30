<?php
session_start();
include_once('../utils/checkAuth.php');
Auth::Check(false);

include_once('../core/database/db.php');
include_once('../core/abastecimento/manager.php');

if (
  !empty($_POST['id_veiculo']) && !empty($_POST['km_atual']) && !empty($_POST['valor']) &&
  !empty($_POST['qtd_litros']) && !empty($_POST['valor_por_litro']) && !empty($_POST['latitude']) && !empty($_POST['longitude'])
) {
  $connection = BancoDeDados::Connect();
  $result = Abastecimento::Create($connection, $_POST['id_veiculo'], $_POST['km_atual'], $_POST['valor'], $_POST['qtd_litros'], $_POST['valor_por_litro'], $_POST['latitude'], $_POST['longitude']);
  if ($result) {
    header("Location: /dashboard.php");
  } else {
    header("Location: /error.php");
  }
} else {
  header("Location: /error.php");
}
