<?php

include_once './conexion.php';
include_once './sesion.php';

if (!isset($_SESSION['IdUsuario'])) {
    header('Location: ../index.php');
}

$preposiciones = ['a', 'ante', 'bajo', 'cabe', 'con', 'contra', 'de', 'desde', 'durante', 'en', 'entre', 'hacia', 'hasta', 'mediante', 'para', 'por', 'segun', 'sin', 'so', 'sobre', 'tras', 'versus', 'via'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $statement = "SELECT * FROM Recurso WHERE ";
    $busqueda = $_GET['busqueda'];
    if (isset($busqueda) && !empty($busqueda)) {
        $palabras = explode(' ', $busqueda);
        $tamano = sizeof($palabras);

        foreach ($preposiciones as $preposicion) {
            if (!empty(array_search($preposicion, $palabras))) {
                unset($palabras[array_search($preposicion, $palabras)]);
            }
        }

        for ($i = 0; $i <= $tamano; $i++) {
            if (isset($palabras[$i])) {
                $statement .= "Descripcion LIKE '%" . $palabras[$i] . "%' OR Nombre LIKE '%" . $palabras[$i] . "%'";

                if ($i < $tamano - 1) {
                    $statement .= " OR ";
                }
            }
        }

        $Busqueda = $pdo->prepare($statement);
        $Busqueda->execute();
        $Busqueda = $Busqueda->fetchAll();
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>REPOSITORIO IPN</title>

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
                <h1 class="section">Resultados para: <?php echo $busqueda; ?></h1>
                <?php if (sizeof($Busqueda) > 0) { ?>
                    <ul class="collapsible">
                        <?php foreach ($Busqueda as $data) { ?>
                            <li>
                                <div class="collapsible-header">
                                    <?php if ($data['Video'] == 1) { ?>
                                        <i class="fab fa-youtube"></i>
                                    <?php } elseif ($data['Video'] == 0) { ?>
                                        <i class="fas fa-file-pdf"></i>
                                    <?php } ?>

                                    <?php
                                    $sql_leer = "SELECT * FROM Escuelas WHERE IdEscuela = :ID LIMIT 1";
                                    $datos = $pdo->prepare($sql_leer);
                                    $datos->execute(array(':ID' => $data['IdEscuela']));
                                    $C_Escuela = $datos->fetch();

                                    $sql_leer = "SELECT * FROM Materias WHERE IdMateria = :ID LIMIT 1";
                                    $datos = $pdo->prepare($sql_leer);
                                    $datos->execute(array(':ID' => $data['IdMateria']));
                                    $C_Materia = $datos->fetch();

                                    $sql_leer = "SELECT * FROM Recurso WHERE IdRecurso = :ID LIMIT 1";
                                    $datos = $pdo->prepare($sql_leer);
                                    $datos->execute(array(':ID' => $data['IdRecurso']));
                                    $C_Nombre = $datos->fetch();

                                    ?>
                                    <?php echo $C_Escuela['N_Escuela'] . " - " . $C_Materia['N_Materia'] . " - " . $C_Nombre['Nombre']; ?>
                                </div>
                                <div class="collapsible-body">
                                    <p>
                                        <?php echo $C_Nombre['Descripcion']; ?>
                                    </p>
                                    <a href="recurso.php?id=<?php echo $data['IdRecurso']; ?>" class="align-right">Ver Recurso</a>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } else { ?>
                    <div class="leyenda">
                        <p><span class="resultados_para">No se encontraron resultados para:</span> <?php echo $busqueda; ?></p>
                    </div>
                <?php } ?>
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