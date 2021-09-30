<?php

session_start();

if(isset($_SESSION['IdUsuario']))
{
    $IdUsuario = $_SESSION['IdUsuario'];
    $_SESSION['IdUsuario'] = $IdUsuario;
}
