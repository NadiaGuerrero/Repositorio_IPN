<?php

include_once './conexion.php';
include_once './sesion.php';

if (!isset($_SESSION['IdUsuario'])) {
    header('Location: ../index.php');
}

$sql_leer = "SELECT * FROM Usuario WHERE IdUsuario = :ID LIMIT 1";
$datos = $pdo->prepare($sql_leer);
$datos->execute(array(':ID' => $IdUsuario));
$consulta = $datos->fetch();

if (isset($consulta) && !empty($consulta)) {
    $IdEscuela = $consulta['IdEscuela'];
    $IdCarrera = $consulta['IdCarrera'];
}

$sql_leer = "SELECT * FROM Escuelas WHERE IdEscuela = :ID LIMIT 1";
$datos = $pdo->prepare($sql_leer);
$datos->execute(array(':ID' => $IdEscuela));
$consulta = $datos->fetch();

if (isset($consulta) && !empty($consulta)) {
    $Escuela = $consulta['N_Escuela'];
}

$sql_leer = "SELECT * FROM Carreras WHERE IdCarrera = :ID LIMIT 1";
$datos = $pdo->prepare($sql_leer);
$datos->execute(array(':ID' => $IdCarrera));
$consulta = $datos->fetch();

if (isset($consulta) && !empty($consulta)) {
    $Carrera = $consulta['N_Carrera'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Archivo = $_FILES["Archivo"];

    $Escuela = trim($_POST['Escuela']);
    $Escuela = filter_var($Escuela, FILTER_SANITIZE_STRING);

    $Carrera = trim($_POST['Carrera']);
    $Carrera = filter_var($Carrera, FILTER_SANITIZE_STRING);

    $Materia = trim($_POST['Materia']);
    $Materia = filter_var($Materia, FILTER_SANITIZE_STRING);

    $Nombre = trim($_POST['Nombre']);
    $Nombre = filter_var($Nombre, FILTER_SANITIZE_STRING);

    $Descripcion = trim($_POST['Descripcion']);
    $Descripcion = filter_var($Descripcion, FILTER_SANITIZE_STRING);

    $Video = trim($_POST['Video']);
    $Video = filter_var($Video, FILTER_SANITIZE_STRING);

    $Link = trim($_POST['Link']);
    $Link = filter_var($Link, FILTER_SANITIZE_STRING);

    //$_FILES["archivo"]["name"];

    $dirUploads = "../files/";
    $archUpload = $dirUploads . $Archivo["name"];

    if (move_uploaded_file($Archivo["tmp_name"], $archUpload)) {
        include_once './conexion.php';

        $consulta = $pdo->prepare("INSERT INTO Recurso (IdUsuario, IdEscuela, IdCarrera, IdMateria, Nombre, Descripcion, Video, Link, Archivo) VALUES(:IdUsuario, :IdEscuela, :IdCarrera, :IdMateria, :Nombre, :Descripcion, :Video, :Link, :Archivo)");
        $consulta->execute(array(':IdUsuario' => $IdUsuario, ':IdEscuela' => $Escuela, ':IdCarrera' => $Carrera, ':IdMateria' => $Materia, ':Nombre' => $Nombre, ':Descripcion' => $Descripcion, ':Video' => $Video, ':Link' => $Link, ':Archivo' => $archUpload));
        
        echo "<script> alert('Se subió el archivo');window.location.href='../index.php';</script>";
    } elseif ($Video == 1) {
        $archUpload = "s/a";
        $consulta = $pdo->prepare("INSERT INTO Recurso (IdUsuario, IdEscuela, IdCarrera, IdMateria, Nombre, Descripcion, Video, Link, Archivo) VALUES(:IdUsuario, :IdEscuela, :IdCarrera, :IdMateria, :Nombre, :Descripcion, :Video, :Link, :Archivo)");
        $consulta->execute(array(':IdUsuario' => $IdUsuario, ':IdEscuela' => $Escuela, ':IdCarrera' => $Carrera, ':IdMateria' => $Materia, ':Nombre' => $Nombre, ':Descripcion' => $Descripcion, ':Video' => $Video, ':Link' => $Link, ':Archivo' => $archUpload));
    
        echo "<script> alert('Se subió el archivo');window.location.href='../index.php';</script>";
    } else {
        echo "<script> alert('No se subió');window.location.href='./subirRecurso.php';</script>";
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
    <?php include('navegacion.php') ?>

    <main>
        <section>
            <div class="row container">
                <h1 class="section">Subir recurso</h1>
                <form id="formUploadFile" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                    <div class="row container">
                        <div class="input-field col s6">
                            <p>Unidad Académica</p>
                            <p><?php echo $Escuela ?></p>
                            <input value=<?php echo $IdEscuela ?> id="disabled" type="hidden" name="Escuela" class="validate">
                        </div>
                        <div class="input-field col s6">
                            <p>Carrera</p>
                            <p><?php echo $Carrera ?></p>
                            <input value=<?php echo $IdCarrera ?> id="disabled" type="hidden" name="Carrera" class="validate">
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">book</i>
                            <select data-validetta="required" name="Materia">
                                <option value="">Selecciona una materia</option>
                                <?php
                                $sql_leer = "SELECT * FROM Materias WHERE IdCarrera = :ID";
                                $datos = $pdo->prepare($sql_leer);
                                $datos->execute(array(':ID' => $IdCarrera));
                                $consulta = $datos->fetchAll();

                                foreach ($consulta as $dato) {
                                ?>
                                    <option value=<?php echo $dato['IdMateria'] ?>><?php echo $dato['N_Materia'] ?></option>
                                <?php } ?>
                            </select>
                            <label>Materia</label>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">create</i>
                            <input id="input_text" type="text" class="validate" name="Nombre" data-length="100" data-validetta="required,maxLength[100]">
                            <label for="input_text">Nombre del recurso</label>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">description</i>
                            <textarea id="textarea2" class="materialize-textarea" name="Descripcion" data-length="500" data-validetta="required,maxLength[500]"></textarea>
                            <label for="textarea2">Descripción del recurso</label>
                        </div>
                        <div class="input-field col s12">
                            <p>¿El recurso incluye un vídeo de Youtube?</p>

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
                                    <input name="Video" type="radio" checked onclick="changeStyle()" value="0" />
                                    <span>No</span>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input name="Video" type="radio" onclick="changeStyle2()" value="1" />
                                    <span>Si</span>
                                </label>
                            </p>
                            <textarea hidden id="Link" placeholder="Inserta el enlace del vídeo de Youtube" class="materialize-textarea" name="Link" data-length="500" data-validetta="required,maxLength[500]"></textarea>
                        </div>
                    </div>
                    <div class="row container">
                        <div class="file-field col s8 left-align">
                            <div class="btn pink darken-4">
                                Archivo
                                <input id="archivo" type="file" accept="application/pdf" name="Archivo">
                            </div>
                            <div class="file-path-wrapper">
                                <input id="archivo" class="file-path validate" type="text" placeholder="Cargue un archivo PDF">
                            </div>
                        </div>
                    </div>
                    <div class="row container">
                        <div class="col s12">
                            <button class="btn waves-effect waves-light pink darken-4" type="submit" name="action">Enviar
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>
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
    <script>
        function changeStyle() {
            var element = document.getElementById("Link");
            element.style.display = "initial";

            var element2 = document.getElementById("archivo");
            element.style.display = "none";
        }

        function changeStyle2() {
            var element = document.getElementById("Link");
            element.style.display = "none";

            var element2 = document.getElementById("archivo");
            element.style.display = "initial";
        }
    </script>
</body>

</html>