<?php
//session_start();
include_once './conexion.php';

$stmt = $pdo->prepare("SELECT * FROM Usuario where Correo=:Correo and Contrasena=:Contrasena");

$stmt->bindParam(':Correo', $Correo);
$stmt->bindParam(':Contrasena', $Contrasena);

$Correo = $_POST["Correo"];
$Contrasena = $_POST["Contrasena"];

$stmt->execute();

$count=$stmt->fetchAll();
$i=0;
foreach ($count as $Id) :
    $IdUsuario = $Id["IdUsuario"];
    $i=1;
endforeach;

if ($i==1) {
    session_start();

    $_SESSION['IdUsuario'] = $IdUsuario;
    $respAX_JSON["IdUsuario"] = $IdUsuario;
    $respAX_JSON["mensaje"] = "success"; 
}else{
    $respAX_JSON["mensaje"] = "error"; 
}
echo json_encode($respAX_JSON);