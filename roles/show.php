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
        $res = $mbd->prepare("SELECT id, nombre FROM roles WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $rol = $res->fetch();

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
    <title>Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h3 class="text-primary">Rol</h3>

            <?php include('../partials/mensajes.php'); ?>

            <?php if(!empty($rol)): ?>
                <table class="table table-hover table-responsive">
                    <tr>
                        <th>Rol:</th>
                        <td><?php echo $rol['nombre']; ?></td>
                    </tr>

                </table>
                <p>
                    <a href="<?php echo ROLES . 'edit.php?id=' . $id; ?>" class="btn btn-outline-primary btn-sm">Editar</a>
                    <a href="<?php echo ROLES; ?>" class="btn btn-link btn-sm">Volver</a>
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