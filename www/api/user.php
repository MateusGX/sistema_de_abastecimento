<?php
session_start();
include_once('../utils/checkAuth.php');
Auth::Check(false);

include_once('../core/database/db.php');
include_once('../core/user/manager.php');

if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) {
  $connection = BancoDeDados::Connect();
  $result = Usuario::Update($connection, $_POST['name'], $_POST['email'], $_POST['password']);
  if ($result) {
    header("Location: /dashboard.php");
  } else {
    header("Location: /error.php");
  }
} else {
  header("Location: /error.php");
}
