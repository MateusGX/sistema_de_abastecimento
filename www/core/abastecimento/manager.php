<?php
class Abastecimento
{
  static function CheckAbastecimento($connection, $id_veiculo)
  {
    $stmt = $connection->prepare("SELECT id FROM veiculo WHERE id=? AND id_usuario=? AND active=1");
    $stmt->bind_param("ss", $id_veiculo, $_SESSION['user_id']);
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
  static function Desactive($connection, $id)
  {
    $stmt = $connection->prepare("UPDATE abastecimento SET active=0 WHERE id_usuario=? AND id=?");
    $stmt->bind_param("ii", $_SESSION['user_id'], $id);
    $stmt->execute();
    $stmt->close();
    return true;
  }
  static function Create($connection, $id_veiculo, $km_atual, $valor, $qtd_litros, $valor_por_litro, $latitude, $longitude)
  {
    if (!self::CheckAbastecimento($connection, $id_veiculo)) {
      $_SESSION['error'] = "Você não tem esse veículo cadastrado!";
      return false;
    }
    $stmt = $connection->prepare("INSERT INTO abastecimento (id_usuario, id_veiculo, km_atual, valor, qtd_litros, valor_por_litro, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iidddddd", $_SESSION['user_id'], $id_veiculo, $km_atual, $valor, $qtd_litros, $valor_por_litro, $latitude, $longitude);
    $stmt->execute();
    $stmt->close();
    return true;
  }
  static function GetAllById($connection, $id)
  {
    if (!self::CheckVehicleById($connection, $id)) {
      return "[]"; // Mandar para error
    }
    $stmt = $connection->prepare("SELECT * FROM abastecimento WHERE id_veiculo=? AND active=1 ORDER BY dt_abastecimento DESC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $row = $result->fetch_all(MYSQLI_ASSOC);
    return json_encode($row);
  }
}
