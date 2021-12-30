<?php
session_start();
include_once('../utils/checkAuth.php');
Auth::Check(false);

include_once('../core/database/db.php');
include_once('../core/vehicle/manager.php');

if (!empty($_POST['placa'])) {
  if(strlen($_POST['placa']) != 7 ){
    header("Location: /error.php");
    return;
  }
  $connection = BancoDeDados::Connect();
  $result = Vehicle::Create($connection, $_POST['placa']);
  if ($result) {
    header("Location: /dashboard.php");
  } else {
    header("Location: /error.php");
  }
} else {
  header("Location: /error.php");
}
