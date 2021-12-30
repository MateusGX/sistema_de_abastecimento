<?php
class Auth
{
  static function Check($isAuthForm)
  {
    if ($isAuthForm) {
      if (isset($_SESSION['authenticated']) || $_SESSION['authenticated'] == true) {
        header("Location: /dashboard.php");
      }
    } else {
      if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] != true) {
        $_SESSION['error'] = "Acesso não autorizado!";
        header("Location: /error.php");
      }
    }
  }
}
