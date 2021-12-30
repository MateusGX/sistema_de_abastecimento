<?php
class BancoDeDados
{
  static private $server = "mysql-server";
  static private $username = "root";
  static private $password = "";
  static private $database = "sistema_entrevista";

  static function Connect()
  {
    $connection = new mysqli(self::$server, self::$username, self::$password, self::$database);
    if ($connection->connect_error) {
      die("ERROR DB: " . $connection->connect_error);
    }

    return $connection;
  }
}
