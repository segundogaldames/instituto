<?php
    require('../class/rutas.php');
    require('../class/conexion.php');
    require('../class/config.php');

    session_start();

    $title = 'Teléfono';

    if (isset($_GET['telefono'])) {
        $id = (int) $_GET['telefono'];

        $res = $mbd->prepare("SELECT t.id, t.numero, t.fijo, t.telefonoable_id, t.telefonoable_type, f.nombre, u.id as usuario FROM funcionarios f INNER JOIN telefonos t ON t.telefonoable_id = f.id INNER JOIN usuarios u ON u.usuarioable_id = f.id WHERE t.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $telefono = $res->fetch();

     }
    /* echo '<pre>';
    print_r($regiones);exit;
    echo '</pre>'; */
?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_id'] == $telefono['usuario']): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE . $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="../js/funciones.js"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h3 class="text-primary"><?php echo $title; ?></h3>
            <?php include('../partials/mensajes.php'); ?>

            <?php if(!empty($telefono)): ?>
                <table class="table table-hover table-responsive">
                    <tr>
                        <th>Número:</th>
                        <td><?php echo $telefono['numero']; ?></td>
                    </tr>
                    <tr>
                        <th>Tipo:</th>
                        <td>
                            <?php
                                if ($telefono['fijo'] == 1) {
                                    echo 'Fijo';
                                }else {
                                    echo 'Móvil';
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td><?php echo $telefono['nombre']; ?></td>
                    </tr>
                    <tr>
                        <th>Rol:</th>
                        <td> <?php echo $telefono['telefonoable_type']; ?></td>
                    </tr>

                </table>
                <p>
                    <a href="<?php echo EDIT_TELEFONO . $id; ?>" class="btn btn-outline-primary btn-sm">Editar</a>
                    <a href="<?php echo FUNCIONARIOS . 'show.php?id=' . $telefono['telefonoable_id'] ; ?>" class="btn btn-link btn-sm">Volver</a>
                    <form name="form" action="<?php echo DEL_TELEFONO; ?>" method="POST">
                         <input type="hidden" name="telefono" value="<?php echo $telefono['id'] ?>">
                         <input type="hidden" name="confirm" value="1">
                         <button type="button" onclick="eliminar();" class="btn btn-outline-warning btn-sm">Eliminar</button>
                    </form>
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
