<?php
session_start();
include_once('../utils/checkAuth.php');
Auth::Check(false);
include_once('../core/database/db.php');
include_once('../core/user/manager.php');

$connection = BancoDeDados::Connect();
$userData = Usuario::GetData($connection);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CONTA</title>
  <link rel="stylesheet" href="/css/main.css">
</head>

<body>
  <header>
    <a href="/dashboard.php">Voltar</a>
  </header>
  <main class="loginContainer">
    <form action="/api/user.php" method="post" autocomplete="off">
      <img src="/img/icon.png" alt="icon">
      <input type="text" name="name" id="name" placeholder="Nome Completo" value="<?php echo $userData['name'];?>" require>
      <input type="email" name="email" id="email" placeholder="E-mail" value="<?php echo $userData['email'];?>" require>
      <input type="password" name="password" id="password" placeholder="Senha" require>
      <input type="submit" value="ATUALIZAR CONTA">
    </form>
  </main>
</body>

</html>