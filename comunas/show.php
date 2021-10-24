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
        $res = $mbd->prepare("SELECT c.id, c.nombre, c.region_id, r.nombre as region FROM regiones r INNER JOIN comunas c ON c.region_id = r.id WHERE c.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $comuna = $res->fetch();


    }

    // echo '<pre>';
    // print_r($comunas);exit;
    // echo '</pre>';
?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador(a)'): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h3 class="text-primary">Comuna</h3>

            <?php include('../partials/mensajes.php'); ?>

            <?php if(!empty($comuna)): ?>
                <table class="table table-hover table-responsive">
                    <tr>
                        <th>Comuna:</th>
                        <td><?php echo $comuna['nombre']; ?></td>
                    </tr>
                    <tr>
                        <th>Región:</th>
                        <td><?php echo $comuna['region']; ?></td>
                    </tr>

                </table>
                <p>
                    <a href="<?php echo COMUNAS . 'edit.php?id=' . $id; ?>" class="btn btn-outline-primary btn-sm">Editar</a>
                    <a href="<?php echo REGIONES . 'show.php?id=' . $comuna['region_id']; ?>" class="btn btn-link btn-sm">Volver</a>
                </p>
            <?php else: ?>
                <p class="text-info">El dato no existe</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
<?php else: ?>
    <?php
        $_SESSION['danger'] = 'Operación no permitida';
        header('Location: ' . BASE_URL);
    ?>
<?php endif; ?>