<?php
session_start();
include_once('./utils/checkAuth.php');
Auth::Check(true);

if (isset($_GET['login'])) {
  $_SESSION['page'] = 'login';
} else if (isset($_GET['signup'])) {
  $_SESSION['page'] = 'signup';
} else {
  $_SESSION['page'] = 'login';
}

if ($_SESSION['page'] != 'login' && $_SESSION['page'] != 'signup') {
  $_SESSION['page'] = 'login';
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo strtoupper($_SESSION['page']); ?></title>
  <link rel="stylesheet" href="/css/main.css">
</head>

<body>
  <?php
  if ($_SESSION['page'] == 'login') {
  ?>
    <main class="loginContainer">
      <form action="/auth/signin.php" method="post" autocomplete="off">
        <img src="/img/icon.png" alt="icon">
        <input type="email" name="email" id="email" placeholder="E-mail">
        <input type="password" name="password" id="password" placeholder="Senha">
        <input type="submit" value="ENTRAR">
        <p>Não tem conta? <a href="/?signup">Crie Já</a></p>
      </form>
    </main>
  <?php
  } else if ($_SESSION['page'] == 'signup') {
  ?>
    <main class="loginContainer">
      <form action="/auth/signup.php" method="post" autocomplete="off">
        <img src="/img/icon.png" alt="icon">
        <input type="text" name="name" id="name" placeholder="Nome Completo" require>
        <input type="email" name="email" id="email" placeholder="E-mail" require>
        <input type="password" name="password" id="password" placeholder="Senha" require>
        <input type="submit" value="CRIAR CONTA">
        <p>Já tem conta? <a href="/?login">Entre Já</a></p>
      </form>
    </main>
  <?php
  }
  ?>
</body>

</html>