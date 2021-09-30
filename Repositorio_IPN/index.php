<?php
include_once './php/conexion.php';
include_once './php/sesion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Correo = trim($_POST['Correo']);
    $Correo = filter_var($Correo, FILTER_SANITIZE_STRING);

    $Contrasena = trim($_POST['Contrasena']);
    $Contrasena = filter_var($Contrasena, FILTER_SANITIZE_STRING);

    $consulta = $pdo->prepare("SELECT * FROM Usuario WHERE Correo = :Correo AND Contrasena = :Contrasena LIMIT 1");
    $consulta->execute(array(':Correo' => $Correo, ':Contrasena' => $Contrasena));
    $datos = $consulta->fetch();

    if (isset($datos) && !empty($datos)) {
        if ($datos['Validado'] == 1) {
            $_SESSION['IdUsuario'] = $datos['IdUsuario'];
            echo "<script>alert('Inicio de sesión exitoso');window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Necesitas verificar tu cuenta para poder iniciar sesión');window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('La cuenta ingresada no existe');window.location.href='index.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Repositorio IPN</title>

    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/validetta.css">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="./css/materialize.css" media="screen,projection" />

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>

    <!-- Modal Structure 1-->
    <div id="modal1" class="modal">
        <div class="modal-content">
            <h4>Iniciar sesión</h4>
            <div class="row">
                <form id="formLogin" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="col s12">
                    <div class="row">
                        <div class="input-field col s12 m12 l6">
                            <i class="material-icons prefix">person</i>
                            <input id="icon_prefix" type="text" name="Correo" class="validate" data-validetta="required,regExp[regemail]">
                            <label for="icon_prefix">Usuario</label>
                        </div>
                        <div class="input-field col s12 m12 l6">
                            <i class="material-icons prefix">lock</i>
                            <input id="icon_telephone" name="Contrasena" type="password" class="validate" data-validetta="required,minLength[8],maxLength[16],regExp[regpass]">
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
        <nav class="  pink darken-4" role="navigation">

            <div class="nav-wrapper">
                <a href="./index.php" class="brand-logo"><img class="brand-logo" draggable="false" height="60px" src="./imgs/IPN.png"></a>
                <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    <li>Hola <?php echo $Nombre ?> </li>
                    <li><a href="./php/subirRecurso.php">Subir Recurso</a></li>
                    <li><a class="white black-text wabes-effect waves-blue-grey lighten-1 btn" href="./php/cerrar.php">Cerrar sesión</a></li>
                </ul>

                <ul class="sidenav" id="nav-mobile">
                    <li>Hola <?php echo $Nombre ?> </li>
                    <li><a href="./php/menu.php">Subir Recurso</a></li>
                    <li><a class="white black-text wabes-effect waves-blue-grey lighten-1 btn" href="./php/cerrar.php">Cerrar sesión</a></li>
                </ul>
            </div>
        </nav>
    <?php } else { ?>
        <!-- Barra de navegación Sin inicio de sesión-->
        <nav class=" nav  pink darken-4" role="navigation">

            <div class="nav-wrapper">
                <a href="./index.php" class="brand-logo"><img class="brand-logo" draggable="false" height="60px" src="./imgs/IPN.png"></a>
                <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    <li><a class="white black-text wabes-effect waves-blue-grey lighten-1 btn modal-trigger" href="#modal1">Iniciar sesión</a></li>
                    <li><a class="white black-text wabes-effect waves-blue-grey lighten-1 btn modal-trigger" href="./php/registro.php">Registrarse</a></li>
                </ul>

                <ul class="sidenav" id="nav-mobile">
                    <li><a class="white black-text wabes-effect waves-blue-grey lighten-1 btn modal-trigger" href="#modal1">Iniciar sesión</a></li>
                    <li><a class="white black-text wabes-effect waves-blue-grey lighten-1 btn modal-trigger" href="./php/registro.php">Registrarse</a></li>
                </ul>
            </div>
        </nav>
    <?php } ?>


    <main>
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <h1 class="center-align">Repositorio Instituto Politécnico Nacional</h1>
                </div>
            </div>
        </div>
        <div class="divider"></div>
        <?php
        if (isset($_SESSION['IdUsuario'])) { ?>
            <br>
            <div class="section row">
                <div class="nav-wrapper container col s12 m12 l4 offset-l4">
                    <form action="./php/busqueda.php" method="GET" autocomplete="on">
                        <div class="input-field">
                            <input id="search" placeholder="¿Que deseas buscar?" name="busqueda" type="search" required>
                            <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                            <i class="material-icons">close</i>
                        </div>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <section>
                <br><br><br>
                <div class="row container">
                    <div class="col s4">
                        <div class="center promo promo-example">
                            <i class="large material-icons">group</i>
                            <p class="promo-caption">Comunidad colaborativa</p>
                            <p class="light center">Ponemos a tu disposición conocimientos que han resultado útiles para otros miembros de la comunidad politécnica.</p>
                        </div>
                    </div>
                    <div class="col s4">
                        <div class="center promo promo-example">
                            <i class="large material-icons">find_in_page</i>
                            <p class="promo-caption">Un lugar para buscar solo lo que te interesa</p>
                            <p class="light center">Inicia sesión con tu correo institucional y encuentra soluciones al instante.</p>
                        </div>
                    </div>
                    <div class="col s4">
                        <div class="center promo promo-example">
                            <i class="large material-icons">settings</i>
                            <p class="promo-caption">La técnica al servicio de la patria</p>
                            <p class="light center">En estos tiempos difíciles, es importante ser solidarios, recuerda que juntos somos más fuertes.</p>
                        </div>
                    </div>
                </div>
                <br><br><br>
            </section>
        <?php } ?>
    </main>

    <footer class="page-footer  pink darken-4">
        <div class="center">
            <h5 class="white-text">Mantente conectado</h5>
            <p class="grey-text text-lighten-4">Síguenos en nuestras redes sociales para descubrir más contenido
                de tu interés</p>
        </div>

        <div class="social-container">
            <a href="#"><i class="fab fa-facebook-square"></i>
            </a>
            <a href="#"><i class="fab fa-instagram"></i>
            </a>
            <a href="#"><i class="fab fa-twitter-square"></i>
            </a>
        </div>

        <div class="footer-copyright">
            <div class="container center footer-text">
                © 2021 FUNKY PUNKYS
            </div>
        </div>
    </footer>

    <!--JavaScript at end of body for optimized loading-->
    <script src="https://kit.fontawesome.com/a779a66931.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="./jscript/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="./jscript/init.js"></script>
    <script type="text/javascript" src="./jscript/materialize.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="./jscript/validetta.js"></script>
    <script type="text/javascript" src="./jscript/validettaLang-es-ES.js"></script>
    <script type="text/javascript" src="./jscript/SweetAlert.js"></script>
    <script>
        $(document).ready(function() {
            $("#formLogin").validetta({
                bubblePosition: 'bottom',
                bubbleGapTop: 10,
                bubbleGapLeft: -5,
                validators: {
                    regExp: {
                        regemail: {
                            pattern: /^(\W|^)[\w.\-]{0,25}@(alumno.ipn|ipn)\.mx(\W|$)$/,
                            errorMessage: 'Solo se permiten correos institucionales IPN'
                        },
                        regpass: {
                            pattern: /^(?=.*\d)(?=.*[\u0021-\u002b\u003c-\u0040])(?=.*[A-Z])(?=.*[a-z])\S{8,16}$/,
                            errorMessage: 'La contraseña debe tener al entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula, al menos una mayúscula y al menos un caracter no alfanumérico'
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>