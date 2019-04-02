<?php

//die(json_encode($_POST['accion'])); //comprobar

$accion = $_POST['accion'];
$password = $_POST['password'];
$usuario = $_POST['usuario'];

if ($accion === 'crear') {
  // Código para logear administradores

  // hashear passwords
  $opciones = array('cost' => 12);

  $hash_password = password_hash($password, PASSWORD_BCRYPT, $opciones);

  // importar la conexión
  include '../funciones/conexion.php';

  try {
    // Realizar la consulta a la base de datos
    $stmt = $conn->prepare(" INSERT INTO usuarios (usuario, password) VALUES (?, ?) ");
    $stmt->bind_param("ss", $usuario, $hash_password);
    $stmt->execute();
    if($stmt->affected_rows > 0){
        $respuesta = array(
          'respuesta' => 'correcto',
          'id_insertado' => $stmt->insert_id,
          'tipo' => $accion
        );
    } else {
      $respuesta = array(
        'respuesta' => 'error'
      );
    }
    $stmt->close();
    $conn->close();

  } catch (Exception $e) {
    // En caso de error, tomar la excepción
    $respuesta = array('pass' => $e->getMessage());
  }

  echo json_encode($respuesta);

}
