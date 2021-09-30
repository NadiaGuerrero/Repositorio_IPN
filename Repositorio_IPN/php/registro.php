<?php

include_once './conexion.php';
include_once './sesion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

if (isset($_SESSION['IdUsuario'])) {
    header('Location: ../index.php');
}

$sql_leer = 'SELECT * FROM Escuelas';

$gsent = $pdo->prepare($sql_leer);
$gsent->execute();

$consulta = $gsent->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $Nombre = trim($_POST['Nombre']);
    $Nombre = filter_var($Nombre, FILTER_SANITIZE_STRING);

    $Apellido = trim($_POST['Apellido']);
    $Apellido = filter_var($Apellido, FILTER_SANITIZE_STRING);

    $IdEscuela = trim($_POST['Escuela']);
    $IdEscuela = filter_var($IdEscuela, FILTER_SANITIZE_STRING);

    $IdCarrera = trim($_POST['Carrera']);
    $IdCarrera = filter_var($IdCarrera, FILTER_SANITIZE_STRING);

    $Rol = trim($_POST['Rol']);
    $Rol = filter_var($Rol, FILTER_SANITIZE_STRING);

    $Correo = trim($_POST['Correo']);
    $Correo = filter_var($Correo, FILTER_SANITIZE_STRING);

    $Contrasena = trim($_POST['Contrasena']);
    $Contrasena = filter_var($Contrasena, FILTER_SANITIZE_STRING);

    $Codigo = Rand(1000, 9999);
    $Validado = 0;

    $sql_leer = "SELECT * FROM Usuario WHERE Correo = :Correo LIMIT 1";
    $datos = $pdo->prepare($sql_leer);
    $datos->execute(array(':Correo' => $Correo));
    $consulta = $datos->fetch();

    if (empty($consulta)) {

        $consulta = $pdo->prepare("INSERT INTO Usuario (Correo, Nombre, Apellido, Contrasena, IdEscuela, IdCarrera, Rol, Codigo, Validado) VALUES(:Correo, :Nombre, :Apellido, :Contrasena, :IdEscuela, :IdCarrera, :Rol, :Codigo, :Validado)");
        $consulta->execute(array(':Correo' => $Correo, ':Nombre' => $Nombre, ':Apellido' => $Apellido, ':Contrasena' => $Contrasena, ':IdEscuela' => $IdEscuela, ':IdCarrera' => $IdCarrera, ':Rol' => $Rol, ':Codigo' => $Codigo, ':Validado' => $Validado));

        $mail = new PHPMailer(true);

        try {
            //Server settings
            //DATOS DEL CORREO.
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'repositorioipn@gmail.com';                     //SMTP username
            $mail->Password   = '$In12345';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;
            $mail->CharSet = 'UTF-8';                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //CUENTA DE USUARIO DESDE LA QUE SE ENVIA EL CORREO
            $mail->setFrom('espositorioipn@gmail.com', 'Repositorio IPN');
            //DESTINO DEL CORREO
            $mail->addAddress($Correo, $Nombre);     //Add a recipient
            // $mail->addAddress('ellen@example.com');               //Name is optional

            //MENSAJE
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Repositorio IPN - Validación';
            $mail->Body = 'Hola ' . $Nombre . ', para continuar con tu registro en el repositorio IPN es necesario que valides el correo, entra a la siguiente dirección e ingresa el código: ' . $Codigo . '<br> <a href ="http://localhost/hackaton/proyecto/php/confirmacion.php?email=' . $Correo . '">Enlace de confirmación</a> <br>Si recibiste este correo por error, ignóralo.<br>-- Repositorio IPN --';

            //ACCION EN CASO DE QUE SE ENVIE CORRECTAMENTE EL CORREO
            $mail->send();
            echo "<script> alert('Correo de validacion enviado, lo encontraras en tu bandeja de inicio');; window.location.href='../index.php';</script>";
            //ACCION EN CASO DE QUE EL CORREO NO SE ENVIE CORRECTAMENTE
        } catch (Exception $e) {
            echo "<script> alert('Correo de validacion no enviado, Error:');{$mail->ErrorInfo} </script>";
        }
    } else {
        echo "<script> alert('Ya hay una cuenta existente con el correo ingresado');</script>";
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

    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/validetta.css">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="../css/materialize.css" media="screen,projection" />

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <nav class=" nav  pink darken-4" role="navigation">
        <div class="nav-wrapper">
            <a href="../index.php" class="brand-logo"><img class="brand-logo" draggable="false" height="60px" src="../imgs/IPN.png"></a>
            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
            </ul>

            <ul class="sidenav" id="nav-mobile">
            </ul>
        </div>
    </nav>

    <!-- Ventana de Terminos y Condiciones -->
    <div id="modal3" class="modal">
        <div class="modal-content">
            <blockquote>
                Términos y Condiciones:<br>
                La misión del Repositorio IPN es mejorar la vida escolar de los politécnicos a través de la enseñanza y el aprendizaje. <br>Permitimos que cualquier miembro de la comunidad académica del IPN pueda compartir sus conocimientos a través de una plataforma que facilite la búsqueda de los antes mencionados.
                Pedimos de la forma mas atenta subir contenido de calidad y solo con fines educativos.<br>
                Queremos que la comunidad use este espacio para compartir contenido educativo, relevante y de calidad. Por lo tanto, aceptas no participar ni ayudar en las conductas que se describen a continuación;<br>
                Subir contenido ilegal, engañoso o fraudulento <br>
                Subir contenido que infrinja los derechos de otras personas, como los relativos a la propiedad intelectual<br>
                Subir virus o códigos maliciosos que afecten al correcto funcionamiento de Tu Repositorio<br>
                Acceder a datos o recopilarlos con medios automatizados (sin nuestro permiso previo), o intentar acceder a ellos sin el permiso correspondiente.<br>
                Para contribuir al correcto funcionamiento de la comunidad, te recomendamos que denuncies cualquier contenido o comportamiento que consideres que vulnera tus derechos (incluidos los derechos de propiedad intelectual) o nuestras condiciones y políticas.
            </blockquote>

        </div>
    </div>

    <main>
        <section>
            <div class="row container">
                <h1 class="section">Registrate</h1>
                <form id="formRegistrar" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="col s12 section">
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">person</i>
                            <input id="first_name" type="text" class="validate" name="Nombre" data-validetta="required,maxLength[25]">
                            <label for="first_name">Nombre</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="last_name" type="text" class="validate" name="Apellido" data-validetta="required,maxLength[25]">
                            <label for="last_name">Apellidos</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">school</i>
                            <select id="UA" data-validetta="required" name="Escuela">
                                <option value="">Selecciona una opción</option>
                                <?php foreach ($consulta as $dato) { ?>
                                    <option value=<?php echo $dato['IdEscuela'] ?>><?php echo $dato['N_Escuela'] ?></option>
                                <?php } ?>
                            </select>
                            <label>Unidad Académica</label>
                        </div>
                        <!-- Selector carrera -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix">school</i>
                            <select id="Carrera" data-validetta="required" name="Carrera">
                                <option value="">Selecciona una opción</option>
                                <?php
                                $sql_leer = "SELECT * FROM Carreras";
                                $gsent = $pdo->prepare($sql_leer);
                                $gsent->execute();

                                $consulta2 = $gsent->fetchAll();
                                foreach ($consulta2 as $dato2) { ?>
                                    <option value=<?php echo $dato2['IdCarrera'] ?>><?php echo $dato2['N_Carrera'] ?></option>
                                <?php } ?>
                            </select>
                            <label>Carrera</label>
                        </div>
                        <div class="col s6">

                        <style>
                                [type="radio"]:checked+span:after,
                                [type="radio"].with-gap:checked+span:before,
                                [type="radio"].with-gap:checked+span:after {
                                    border: 2px solid #A21D5D;
                                }

                                [type="radio"]:checked+span:after,
                                [type="radio"].with-gap:checked+span:after {
                                    background-color: #A21D5D;
                                }
                        </style>

                            <p>
                                <label>
                                    <input name="Rol" type="radio" class="pink darken-4" value=0 checked />
                                    <span>Estudiante</span>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input name="Rol" type="radio" class="pink darken-4" value=1 />
                                    <span>Profesor</span>
                                </label>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">email</i>
                            <input id="email" type="email" class="validate" name="Correo" data-validetta="required,regExp[regemail]">
                            <label for="email">Correo electrónico</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">lock</i>
                            <input id="password" type="password" class="validate" name="Contrasena" data-validetta="required,minLength[8],maxLength[16],regExp[regpass],equalTo[Contrasena]">
                            <label for="password">Contraseña</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix">lock</i>
                            <input id="verification" type="Password" class="validate" name="Verificacion" data-validetta="required,minLength[8],maxLength[16],regExp[regpass],equalTo[Contrasena]">
                            <label for="verification">Confirmacion de contraseña</label>
                        </div>
                    </div>
                    <!--Terminos y condiciones -->
                    <div class="row">
                        <p>
                            <label>
                                <input type="checkbox" />
                                <span>Acepto los Terminos y Condiciones</span>
                            </label>
                        </p>
                        <p>
                            <label>
                                <a class="modal-trigger" href="#modal3">*Terminos y condiciones</a>
                            </label>
                        </p>
                    </div>
                    <button class="btn waves-effect waves-light pink darken-4" type="submit" name="action">Continuar
                        <i class="material-icons right">send</i>
                    </button>
                </form>
            </div>
        </section>
    </main>

    <?php include('footer.php') ?>


    <!--JavaScript at end of body for optimized loading-->
    <script src="https://kit.fontawesome.com/a779a66931.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../jscript/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../jscript/materialize.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../jscript/init.js"></script>
    <script type="text/javascript" src="../jscript/validetta.js"></script>
    <script type="text/javascript" src="../jscript/validettaLang-es-ES.js"></script>
    <script type="text/javascript" src="../jscript/SweetAlert.js"></script>
    <script>
        $(document).ready(function() {
            $("#formRegistrar").validetta({
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