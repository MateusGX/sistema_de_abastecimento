<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Error</title>
  <link rel="stylesheet" href="/css/main.css">
</head>

<body>
  <main class="loginContainer">
    <form action="/?login" method="get" autocomplete="off">
      <img src="/img/icon.png" alt="icon">
      <h1>Ocorreu um Erro :'(</h1>
      <p>
        <?php
        echo $_SESSION['error'];
        $_SESSION['error'] = "";
        ?>
      </p>
      <input type="submit" value="VOLTAR">
    </form>
  </main>
</body>

</html>