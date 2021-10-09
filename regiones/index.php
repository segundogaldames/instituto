<?php
    #muestran errores de codigo de php en tiempo de ejecucion
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    $res = $mbd->query("SELECT id, nombre, codigo FROM regiones ORDER BY nombre");
    $regiones = $res->fetchall();


    // echo '<pre>';
    // print_r($regiones);exit;
    // echo '</pre>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regiones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-6 offset-md-3">
            <?php include('../partials/mensajes.php'); ?>

            <h3 class="text-primary">Lista de Regiones | <a href="<?php echo REGIONES . 'add.php'; ?>" class="btn btn-link">Nueva Región</a> </h3>
            <table class="table table-hover table-responsive">
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Código</th>
                </tr>
                <?php foreach($regiones as $region): ?>
                    <tr>
                        <td><?php echo $region['id']; ?></td>
                        <td>
                            <a href="<?php echo REGIONES . 'show.php?id=' . $region['id']; ?>">
                                <?php echo $region['nombre'];?>
                            </a>

                        </td>
                        <td><?php echo $region['codigo'];?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

    </div>

</body>
</html>