<?php
    #muestran errores de codigo de php en tiempo de ejecucion
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        $rut = trim(strip_tags($_POST['rut']));

        $res = $mbd->prepare("SELECT f.id, f.nombre, r.nombre as rol, c.nombre as comuna FROM funcionarios f INNER JOIN roles r ON f.rol_id = r.id INNER JOIN comunas c ON c.id = f.comuna_id WHERE rut = ? ORDER BY nombre");
        $res->bindParam(1, $rut);
        $res->execute();
        $funcionario = $res->fetch();
    }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-10 offset-md-1">
            <?php include('../partials/mensajes.php'); ?>
            <div class="row">
                <div class="col-md-6">
                    <h3 class="text-primary">
                        Lista de Funcionarios <a href="<?php echo FUNCIONARIOS . 'add.php'; ?>" class="btn btn-outline-primary">Nuevo Funcionario</a>

                    </h3>
                </div>
                <div class="col-md-6">
                    <form class="d-flex" action="" method="POST">
                        <input type="hidden" name="confirm" value="1">
                        <input class="form-control" type="search" name="rut" placeholder="Ingresa el RUT" aria-label="Search">
                        <button class="btn btn-outline-primary" type="submit">Buscar</button>
                    </form>
                </div>
            </div>

            <hr>
            <?php if(isset($funcionario)): ?>
                <table class="table table-hover table-responsive">
                    <tr>
                        <th>Nombre</th>
                        <th>Rol</th>
                        <th>Comuna Residencia</th>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?php echo FUNCIONARIOS . 'show.php?id=' . $funcionario['id']; ?>">
                                <?php echo $funcionario['nombre'];?>
                            </a>
                        </td>
                        <td><?php echo $funcionario['rol']; ?></td>
                        <td><?php echo $funcionario['comuna']; ?></td>
                    </tr>
                </table>
            <?php else: ?>
                <p class="text-info">No hay funcionario registrado</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>