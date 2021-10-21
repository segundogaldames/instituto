<?php
    require('class/rutas.php');
    require('class/config.php');

    session_start();

    $title = 'Bienvenid@s';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE . $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('partials/menu.php'); ?>

    <div class="container">
        <?php include('partials/mensajes.php'); ?>
        <h4><?php echo $title; ?></h4>

        <?php if(isset($_SESSION['autenticado'])): ?>
            <p>Bienvenid@ <?php echo $_SESSION['usuario_nombre']; ?></p>
        <?php endif; ?>

    </div>

</body>
</html>