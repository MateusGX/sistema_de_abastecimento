<?php
session_start();
include_once('../core/database/db.php');
include_once('../core/user/manager.php');

if (!empty($_POST['email']) && !empty($_POST['password'])) {
  $connection = BancoDeDados::Connect();
  $result = Usuario::SignIn($connection, $_POST['email'], $_POST['password']);
  if ($result) {
    header("Location: /dashboard.php");
  } else {
    header("Location: /error.php");
  }
} else {
  header("Location: /error.php");
}
