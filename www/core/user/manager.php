<?php
class Usuario
{
  static function CheckUser($connection, $email)
  {
    $stmt = $connection->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result();
    $stmt->close();

    return mysqli_num_rows($user) == 1;
  }
  static function CheckUserUpdate($connection, $email)
  {
    $stmt = $connection->prepare("SELECT * FROM users WHERE email=? AND id<>?");
    $stmt->bind_param("si", $email, $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result();
    $stmt->close();

    return mysqli_num_rows($user) > 0;
  }
  static function SignUp($connection, $nome, $email, $senha)
  {
    if (self::CheckUser($connection, $email)) {
      $_SESSION['error'] = "Este e-mail já está cadastrado!";
      return false;
    }
    $stmt = $connection->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, strtolower($email), password_hash($senha, PASSWORD_DEFAULT));
    $stmt->execute();
    $stmt->close();
    return true;
  }
  static function Update($connection, $nome, $email, $senha)
  {
    if (self::CheckUserUpdate($connection, $email)) {
      $_SESSION['error'] = "Este e-mail já está cadastrado!";
      return false;
    }
    $stmt = $connection->prepare("UPDATE users SET name=?, email=?, password=? WHERE id=?");
    $stmt->bind_param("sssi", $nome, strtolower($email), password_hash($senha, PASSWORD_DEFAULT), $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    return true;
  }
  static function GetData($connection)
  {
    $stmt = $connection->prepare("SELECT name, email FROM users WHERE id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result();
    $stmt->close();
    if (mysqli_num_rows($user) == 1) {
      $result = $user->fetch_assoc();
      return $result;
    }
    $_SESSION['error'] = "Dados incorretos!";
    return false;
  }
  static function SignIn($connection, $email, $senha)
  {
    $stmt = $connection->prepare("SELECT id, password FROM users WHERE email=?");
    $stmt->bind_param("s", strtolower($email));
    $stmt->execute();
    $user = $stmt->get_result();
    $stmt->close();
    if (mysqli_num_rows($user) == 1) {
      $data = $user->fetch_row();
      if (password_verify($senha, $data[1])) {
        $_SESSION['user_id'] = $data[0];
        $_SESSION['authenticated'] = true;
        return true;
      }
    }
    $_SESSION['error'] = "Dados incorretos!";
    return false;
  }
  static function SignOut()
  {
    session_destroy();
  }
}
