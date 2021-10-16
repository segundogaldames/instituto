<?php
    require('../class/rutas.php');
    require('../class/conexion.php');
    require('../class/config.php');

    session_start();

    $title = 'Editar Usuario';

    if (isset($_GET['usuario'])) {
        $usuario_id = (int) $_GET['usuario'];

        $res = $mbd->prepare("SELECT u.id, u.email, u.activo, u.usuarioable_id, f.nombre, f.rut FROM usuarios u INNER JOIN funcionarios f ON f.id = u.usuarioable_id WHERE u.id = ? AND u.usuarioable_type = 'Funcionario'");
        $res->bindParam(1, $usuario_id);
        $res->execute();
        $usuario = $res->fetch();

        //print_r($usuario);exit;
        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $activo = filter_var($_POST['activo'], FILTER_VALIDATE_INT);

            if (!$email) {
                $msg = 'Ingrese el correo electrónico';
            }elseif (!$activo) {
                $msg = 'Seleccione el status';
            }else {
                #modificar la cuenta de usuario
                $res = $mbd->prepare("UPDATE usuarios SET email = ?, activo = ? WHERE id = ?");
                $res->bindParam(1, $email);
                $res->bindParam(2, $activo);
                $res->bindParam(3, $usuario_id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El usuario se ha modificado correctamente';
                    header('Location: ' . FUNCIONARIOS . 'show.php?id=' . $usuario['usuarioable_id']);
                }
            }
        }
    }

    /* echo '<pre>';
    print_r($regiones);exit;
    echo '</pre>'; */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE . $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h3 class="text-primary"><?php echo $title; ?></h3>

            <p>Campos obligatorios <span class="text-danger">*</span></p>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if($usuario): ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">RUT: <?php echo $usuario['rut']; ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Nombre: <?php echo $usuario['nombre']; ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $usuario['email']; ?>">
                        <div id="emailHelp" class="form-text text-danger">Ingresa el email que deseas modificar.</div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status<span class="text-danger">*</span></label>
                        <select name="activo" class="form-control" id="">
                            <option value="<?php echo $usuario['activo']; ?>">
                                <?php if($usuario['activo'] == 1) echo 'Activo';else echo 'Inactivo'; ?>
                            </option>

                            <option value="">Seleccione...</option>

                            <option value="1">Activar</option>
                            <option value="2">Desactivar</option>

                        </select>
                         <div id="emailHelp" class="form-text text-danger">Selecciona el status que deseas modificar.</div>
                    </div>
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-primary">Editar</button>
                    <a href="<?php echo FUNCIONARIOS . 'show.php?id=' . $usuario['usuarioable_id']; ?>" class="btn btn-link">Volver</a>
                </form>
            <?php else: ?>
                <p class="text-info">La cuenta no se puede crear</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
