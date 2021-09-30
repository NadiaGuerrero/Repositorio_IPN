<?php

include_once './conexion.php';

$stmt = $pdo->prepare("INSERT INTO Usuario (Nombre, Apellido, Correo, Contrasena, IdEscuela, IdCarrera, Rol, Codigo, Validado)
  VALUES (:Nombre, :Apellido, :Correo, :Contrasena, :IdEscuela, :IdCarrera, :Rol, :Codigo, :Validado)");

$stmt->bindParam(':Nombre', $Nombre);
$stmt->bindParam(':Apellido', $Apellido);
$stmt->bindParam(':Correo', $Correo);
$stmt->bindParam(':Contrasena', $Contrasena);
$stmt->bindParam(':IdEscuela', $IdEscuela);
$stmt->bindParam(':IdCarrera', $IdCarrera);
$stmt->bindParam(':Rol', $Rol);
$stmt->bindParam(':Codigo', $Codigo);
$stmt->bindParam(':Validado', $Validado);

$Nombre = $_POST["Nombre"];
$Apellido = $_POST["Apellido"];
$Correo = $_POST["Correo"];
$Contrasena = $_POST["Contrasena"];
$IdEscuela = $_POST["Escuela"];
$IdCarrera = $_POST["Carrera"];
$Rol = $_POST["Rol"];

$Codigo = rand(1000,9999);
$Validado = 0;

$stmt->execute();

$respAX_JSON["mensaje"] = "success";
echo json_encode($respAX_JSON);
