<?php
session_start();
include_once('../core/database/db.php');
include_once('../core/user/manager.php');

if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) {
  $connection = BancoDeDados::Connect();
  $result = Usuario::SignUp($connection, $_POST['name'], $_POST['email'], $_POST['password']);
  if ($result) {
    header("Location: /?login");
  } else {
    header("Location: /error.php");
  }
} else {
  header("Location: /error.php");
}
