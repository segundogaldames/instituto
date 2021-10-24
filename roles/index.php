<?php
    #muestran errores de codigo de php en tiempo de ejecucion
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    $res = $mbd->query("SELECT id, nombre FROM roles ORDER BY nombre");
    $roles = $res->fetchall();

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
            <?php include('../partials/mensajes.php'); ?>

            <h3 class="text-primary">Lista de Roles <a href="<?php echo ROLES . 'add.php'; ?>" class="btn btn-outline-primary">Nuevo Rol</a> </h3>

            <?php if(count($roles)): ?>
                <table class="table table-hover table-responsive">
                    <tr>
                        <th>Nombre</th>
                    </tr>
                    <?php foreach($roles as $rol): ?>
                        <tr>
                            <td>
                                <a href="<?php echo ROLES . 'show.php?id=' . $rol['id']; ?>">
                                    <?php echo $rol['nombre'];?>
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p class="text-info">No hay roles registrado</p>
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