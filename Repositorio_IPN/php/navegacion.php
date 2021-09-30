<!-- Modal Structure 1-->
<div id="modal1" class="modal">
    <div class="modal-content">
        <h4>Iniciar sesión</h4>
        <div class="row">
            <form id="formLogin" class="col s12">
                <div class="row">
                    <div class="input-field col s12 m12 l6">
                        <i class="material-icons prefix">person</i>
                        <input id="icon_prefix" type="text" name="Correo" class="validate" data-validetta="required">
                        <label for="icon_prefix">Usuario</label>
                    </div>
                    <div class="input-field col s12 m12 l6">
                        <i class="material-icons prefix">lock</i>
                        <input id="icon_telephone" name="Contrasena" type="password" class="validate" data-validetta="required">
                        <label for="icon_telephone">Contraseña</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <a href="./php/registro.php" class="waves-effect waves-purple btn-flat left">Registrarse</a>
                        <a href="#!" class="modal-close waves-effect waves-red btn-flat">Salir</a>
                        <button id="submit" class="waves-effect waves-green btn-flat" name="action">Continuar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Header con sesiones -->
<?php
if (isset($_SESSION['IdUsuario'])) {

    $sql_leer = 'SELECT Nombre FROM Usuario where IdUsuario=' . $_SESSION['IdUsuario'];

    $gsent = $pdo->prepare($sql_leer);
    $gsent->execute();

    $consulta = $gsent->fetchAll();

    foreach ($consulta as $Nombre) :
        $Nombre = $Nombre["Nombre"];
    endforeach;
?>

    <!-- Barra de navegación con inicio de sesión-->
    <nav class=" nav  pink darken-4" role="navigation">

        <div class="nav-wrapper">
            <a href="../index.php" class="brand-logo"><img class="brand-logo" draggable="false" height="60px" src="../imgs/IPN.png"></a>
            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li>Hola <?php echo $Nombre ?> </li>
                <li><a href="./subirRecurso.php">Subir Recurso</a></li>
                <li><a class="white black-text wabes-effect waves-blue-grey lighten-1 btn" href="./cerrar.php">Cerrar sesión</a></li>
            </ul>

            <ul class="sidenav" id="nav-mobile">
                <li>Hola <?php echo $Nombre ?> </li>
                <li><a href="./subirRecurso.php">Subir Recurso</a></li>
                <li><a class="white black-text wabes-effect waves-blue-grey lighten-1 btn" href="./cerrar.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>
<?php } else { ?>
    <!-- Barra de navegación Sin inicio de sesión-->
    <nav class=" nav  pink darken-4" role="navigation">

        <div class="nav-wrapper">
            <a href="../index.php" class="brand-logo"><img class="brand-logo" draggable="false" height="60px" src="../imgs/IPN.png"></a>
            <!-- <a href="./index.php"><img class="brand-logo" draggable="false" src="./imgs/LOGO_CAFE4.png"></a> -->
            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li><a class="white black-text wabes-effect waves-blue-grey lighten-1 btn modal-trigger" href="#modal1">Iniciar sesión</a></li>
                <li><a class="white black-text wabes-effect waves-blue-grey lighten-1 btn modal-trigger" href="./registro.php">Registrarse</a></li>
            </ul>

            <ul class="sidenav" id="nav-mobile">
                <li><a class="white black-text wabes-effect waves-blue-grey lighten-1 btn modal-trigger" href="#modal1">Iniciar sesión</a></li>
                <li><a class="white black-text wabes-effect waves-blue-grey lighten-1 btn modal-trigger" href="./registro.php">Registrarse</a></li>
            </ul>
        </div>
    </nav>
<?php } ?>