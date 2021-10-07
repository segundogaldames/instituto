<?php
    #muestran errores de codigo de php en tiempo de ejecucion
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    #valdamos el valor del id que se ha enviado desde el index
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        #consultar en la tabla regiones si hay una region con este id
        $res = $mbd->prepare("SELECT id, nombre, codigo FROM regiones WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $region = $res->fetch();

        //print_r($region);
        #lista de comunas por region
        $res = $mbd->prepare("SELECT id, nombre, region_id FROM comunas WHERE region_id = ? ORDER BY nombre");
        $res->bindParam(1, $id);
        $res->execute();
        $comunas = $res->fetchall();
    }

    // echo '<pre>';
    // print_r($comunas);exit;
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
            <h3 class="text-primary">Regiones</h3>

            <?php include('../partials/mensajes.php'); ?>

            <?php if(!empty($region)): ?>
                <table class="table table-hover table-responsive">
                    <tr>
                        <th>Id:</th>
                        <td><?php echo $region['id']; ?></td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td><?php echo $region['nombre']; ?></td>
                    </tr>
                    <tr>
                        <th>Código:</th>
                        <td><?php echo $region['codigo']; ?></td>
                    </tr>

                </table>
                <p>
                    <a href="<?php echo REGIONES . 'edit.php?id=' . $region['id']; ?>" class="btn btn-outline-primary btn-sm">Editar</a>
                    <a href="<?php echo COMUNAS . 'add.php?region=' . $id; ?>" class="btn btn-outline-secondary btn-sm">Agregar Comuna</a>
                    <a href="<?php echo REGIONES; ?>" class="btn btn-link btn-sm">Volver</a>
                    <form action="" method="post">

                    </form>
                </p>
            <?php else: ?>
                <p class="text-info">El dato no existe</p>
            <?php endif; ?>
        </div>

        <!-- lista de comunas asociadas a la region -->
        <div class="col-md-6 offset-md-3">
            <h4 class="text-secondary">Comunas de la Región <?php echo $region['nombre']; ?> </h4>

            <?php if(!empty($comunas)): ?>
                <div class="list-group">
                    <?php foreach($comunas as $comuna): ?>
                        <a href="#" class="list-group-item list-group-item-action"><?php echo $comuna['nombre']; ?></a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-info">No hay comunas asociadas</p>
            <?php endif; ?>

        </div>

    </div>

</body>
</html>