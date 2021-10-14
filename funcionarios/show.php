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

        #consultar en la tabla funcionarios si hay un funcionario con este id
        $res = $mbd->prepare("SELECT f.id, f.rut, f.nombre, f.fecha_nacimiento, f.email, f.direccion, r.nombre as rol, c.nombre as comuna FROM funcionarios f INNER JOIN roles r ON f.rol_id = r.id INNER JOIN comunas c ON c.id = f.comuna_id WHERE f.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $funcionario = $res->fetch();

        #funcionario con verificacion de cuenta
        $res = $mbd->prepare("SELECT id, activo FROM usuarios WHERE usuarioable_id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $usuario = $res->fetch();

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
    <title>Funcionarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h3 class="text-primary">Funcionario</h3>

            <?php include('../partials/mensajes.php'); ?>

            <?php if(!empty($funcionario)): ?>
                <table class="table table-hover table-responsive">
                    <tr>
                        <th>RUT:</th>
                        <td><?php echo $funcionario['rut']; ?></td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td><?php echo $funcionario['nombre']; ?></td>
                    </tr>
                    <tr>
                        <th>Fecha de Nacimiento:</th>
                        <td>
                            <?php
                                $fecha = new Datetime($funcionario['fecha_nacimiento']);
                                echo $fecha->format('m-m-Y')
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo $funcionario['email']; ?></td>
                    </tr>
                    <tr>
                        <th>Dirección:</th>
                        <td><?php echo $funcionario['direccion']; ?></td>
                    </tr>
                    <tr>
                        <th>Rol:</th>
                        <td><?php echo $funcionario['rol']; ?></td>
                    </tr>
                    <tr>
                        <th>Comuna:</th>
                        <td><?php echo $funcionario['comuna']; ?></td>
                    </tr>
                    <?php if($usuario): ?>
                        <tr>
                            <th>Activo:</th>
                            <td>
                                <?php
                                    if ($usuario['activo'] == 1) {
                                        echo 'Si';
                                    }else{
                                        echo 'No';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
                <p>
                    <a href="<?php echo FUNCIONARIOS . 'edit.php?id=' . $id; ?>" class="btn btn-outline-primary btn-sm">Editar</a>

                    <!-- verificamos que el usuario tenga una cuenta -->
                    <?php if(empty($usuario)): ?>
                        <a href="<?php echo USUARIOS . 'add.php?funcionario=' . $id; ?>" class="btn btn-outline-success btn-sm">Crear Cuenta</a>
                    <?php else: ?>
                        <a href="<?php echo USUARIOS . 'edit.php?id=' . $usuario['id']; ?>" class="btn btn-outline-success btn-sm">Modificar Cuenta</a>
                    <?php endif; ?>

                    <a href="<?php echo FUNCIONARIOS; ?>" class="btn btn-link btn-sm">Volver</a>
                </p>
            <?php else: ?>
                <p class="text-info">El dato no existe</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>