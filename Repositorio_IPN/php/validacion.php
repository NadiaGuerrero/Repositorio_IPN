<?php

include_once './conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Codigo = trim($_POST['Codigo']);
    $Codigo = filter_var($Codigo, FILTER_SANITIZE_STRING);

    $Correo = trim($_POST['Correo']);
    $Correo = filter_var($Correo, FILTER_SANITIZE_STRING);

    $sql_leer = "SELECT * FROM Usuario WHERE Correo = :Correo LIMIT 1";
    $datos = $pdo->prepare($sql_leer);
    $datos->execute(array(':Correo' => $Correo));
    $consulta = $datos->fetch();

    if (isset($consulta) && !empty($consulta)) {
        if ($consulta['Codigo'] == $Codigo) {
            $sql_leer = 'UPDATE Usuario SET Validado = 1 WHERE Correo = :Correo';
            $datos = $pdo->prepare($sql_leer);
            $datos->execute(array(':Correo' => $Correo));
            echo "<script>alert('La cuenta ha sido validada');window.location.href='../index.php';</script>";
        }
    }
}
