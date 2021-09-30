<?php

include_once './conexion.php';
include_once './sesion.php';

if (!isset($_SESSION['IdUsuario'])) {
    header('Location: ../index.php');
}

if (isset($_GET['id'])) {
    $IdRecurso = $_GET['id'];
}

$sql_leer = "SELECT * FROM Recurso WHERE IdRecurso = :ID LIMIT 1";
$datos = $pdo->prepare($sql_leer);
$datos->execute(array(':ID' => $IdRecurso));
$Recurso = $datos->fetch();

$sql_leer = "SELECT * FROM Escuelas WHERE IdEscuela = :ID LIMIT 1";
$datos = $pdo->prepare($sql_leer);
$datos->execute(array(':ID' => $Recurso['IdEscuela']));
$Escuela = $datos->fetch();

$sql_leer = "SELECT * FROM Materias WHERE IdMateria = :ID LIMIT 1";
$datos = $pdo->prepare($sql_leer);
$datos->execute(array(':ID' => $Recurso['IdMateria']));
$Materia = $datos->fetch();

$sql_leer = "SELECT * FROM Carreras WHERE IdCarrera = :ID LIMIT 1";
$datos = $pdo->prepare($sql_leer);
$datos->execute(array(':ID' => $Recurso['IdCarrera']));
$Carrera = $datos->fetch();
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
    <?php include('navegacion.php') ?>
    <main>
        <section>
            <div class="row container">
                <div class="col s12">
                    <h4 class="left-align">Titulo de Recurso: <?php echo $Recurso['Nombre']; ?></h4>
                </div>
                <div class="col s12">
                    <h6 class="left-align">Unidad Academica: <?php echo $Escuela['N_Escuela']; ?></h6>
                </div>
                <div class="col s12">
                    <h6 class="left-align">Carrera: <?php echo $Carrera['N_Carrera']; ?></h6>
                </div>
                <div class="col s12">
                    <h6 class="left-align">Materia: <?php echo $Materia['N_Materia']; ?></h6>
                </div>
            </div>
            <div class="divider"></div>
            <div class="row container">
                <section>
                    <div class="col s12 center-align">
                        <br>
                        <?php if ($Recurso['Video'] == 1) { ?>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $Recurso['Link']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <?php } elseif ($Recurso['Video'] == 0) { ?>
                            <i class="fas fa-file-pdf"></i>
                            <a href="<?php echo $Recurso['Archivo']; ?>">Abrir PDF</a>
                        <?php } ?>
                    </div>
                </section>
                <div class="col s12">
                    <h6 class="left-align">Descripción:</h6>
                    <blockquote>
                        <?php echo $Recurso['Descripcion']; ?>
                    </blockquote>
                </div>
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
            $('input#input_text, textarea#textarea2').characterCounter();
        });

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