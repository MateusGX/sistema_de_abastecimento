<?php
class Vehicle
{
  static function CheckVehicle($connection, $placa)
  {
    $stmt = $connection->prepare("SELECT id FROM veiculo WHERE placa=? AND id_usuario=? AND active=1");
    $stmt->bind_param("ss", $placa, $_SESSION['user_id']);
    $stmt->execute();
    $vehicle = $stmt->get_result();
    $stmt->close();

    return mysqli_num_rows($vehicle) == 1;
  }
  static function CheckVehicleById($connection, $id)
  {
    $stmt = $connection->prepare("SELECT id FROM veiculo WHERE id=? AND id_usuario=? AND active=1");
    $stmt->bind_param("ss", $id, $_SESSION['user_id']);
    $stmt->execute();
    $vehicle = $stmt->get_result();
    $stmt->close();

    return mysqli_num_rows($vehicle) == 1;
  }
  static function Create($connection, $placa)
  {
    if (self::CheckVehicle($connection, $placa)) {
      $_SESSION['error'] = "Um veículo com esta placa já está cadastrado!";
      return false;
    }
    $stmt = $connection->prepare("INSERT INTO veiculo (id_usuario, placa) VALUES (?, ?)");
    $stmt->bind_param("is", $_SESSION['user_id'], $placa);
    $stmt->execute();
    $stmt->close();
    return true;
  }
  static function Desactive($connection, $id)
  {
    $stmt = $connection->prepare("UPDATE veiculo SET active=false WHERE id_usuario=? AND id=?");
    $stmt->bind_param("ii", $_SESSION['user_id'], $id);
    $stmt->execute();
    $stmt->close();
    return true;
  }
  static function Update($connection, $id, $placa)
  {
    if (!self::CheckVehicleById($connection, $id)) {
      return false; // Mandar para error
    }
    if (self::CheckVehicle($connection, $placa)) {
      $_SESSION['error'] = "Um veículo com esta placa já está cadastrado!";
      return false;
    }
    $stmt = $connection->prepare("UPDATE veiculo SET placa=? WHERE id=?");
    $stmt->bind_param("si", $placa, $id);
    $stmt->execute();
    $stmt->close();
    return true;
  }
  static function GetAll($connection)
  {
    $stmt = $connection->prepare("SELECT * FROM veiculo WHERE id_usuario=? AND active=1");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $html = "";
    while ($row = mysqli_fetch_assoc($result)) {
      $html .= "<div class=\"vehicle\"><div class=\"placa\">{$row['placa']}</div><div class=\"actions\"><a href=\"/dashboard/vehicle.php?view={$row['id']}\">ABASTECIMENTO</a><a href=\"/dashboard/vehicle.php?edit={$row['id']}\">EDITAR</a><a href=\"/api/vehicle.php?active&id={$row['id']}\">DESATIVAR</a></div></div>";
    }
    $stmt->close();
    return $html;
  }
  static function GetById($connection, $id)
  {
    if (!self::CheckVehicleById($connection, $id)) {
      return "ERROR"; // Mandar para error
    }
    $stmt = $connection->prepare("SELECT id, placa FROM veiculo WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if (mysqli_num_rows($result) == 1) {
      $row = $result->fetch_assoc();
      return $row['placa'];
    }
    return "ERROR";
  }
}
