<?php

include_once './conexion.php';
include_once './sesion.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];
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
                <h1 class="section">Verifica tu cuenta</h1>
                <h4 class="section">Ingresa el código enviado a tu correo</h4>
                <form id="formRegistrar" action="./validacion.php" method="POST" class="col s12 section">
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">check_circle</i>
                            <input id="codigo" type="number" class="validate" name="Codigo" data-validetta="required,maxLength[4],minLength[4]">
                            <label for="codigo">Código de verificación</label>
                        </div>
                    </div>
                    <input value=<?php echo $email ?> id="disabled" type="hidden" name="Correo" class="validate">

                    <button class="btn waves-effect waves-light pink darken-4" type="submit" name="action">Continuar
                        <i class="material-icons right">send</i>
                    </button>
                </form>
        </section>
    </main>

    <?php include('footer.php') ?>

    <!--JavaScript at end of body for optimized loading-->
    <script src="https://kit.fontawesome.com/a779a66931.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../jscript/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../jscript/materialize.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../ajax/ajaxInicio.js"></script>
    <script src="../ajax/ajaxRegistro.js"></script>
    <script src="../jscript/init.js"></script>
    <script type="text/javascript" src="../jscript/validetta.js"></script>
    <script type="text/javascript" src="../jscript/validettaLang-es-ES.js"></script>
    <script>
        $(document).ready(function() {
            $("#formRegistrar").validetta({
                bubblePosition: 'bottom',
                bubbleGapTop: 10,
                bubbleGapLeft: -5
            });
        });
    </script>
</body>

</html>