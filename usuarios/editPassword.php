<?php
    require('../class/rutas.php');
    require('../class/conexion.php');
    require('../class/config.php');

    session_start();

    //print_r($_SESSION);

    $title = 'Cambiar Password';

    if (isset($_GET['usuario'])) {
        $id_usuario = (int) $_GET['usuario'];
        echo '<br>';

        $res = $mbd->prepare("SELECT u.id, f.nombre FROM usuarios u INNER JOIN funcionarios f ON f.id = u.usuarioable_id WHERE u.id = ?");
        $res->bindParam(1, $id_usuario);
        $res->execute();
        $usuario = $res->fetch();

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
           $clave = trim(strip_tags($_POST['clave']));
           $reclave = trim(strip_tags($_POST['reclave']));

           if (strlen($clave) < 8) {
              $msg = 'El password debe tener al menos 8 caracteres';
           }elseif ($reclave != $clave) {
               $msg = 'Los passwords ingresados no coinciden';
           }else{
                $clave = sha1($clave);
                #modificamos el password
                $res = $mbd->prepare("UPDATE usuarios SET clave = ? WHERE id = ?");
                $res->bindParam(1, $clave);
                $res->bindParam(2, $id_usuario);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El password se ha modificado correctamente';
                    header('Location: ' . BASE_URL);
                }
           }

        }

    }

    /* echo '<pre>';
    print_r($regiones);exit;
    echo '</pre>'; */
?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador(a)' || $_SESSION['usuario_id'] == $id_usuario): ?>

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
                    <div class="mb">
                        <label for="usuario" class="form-label">Usuario: <?php echo $usuario['nombre']; ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Password<span class="text-danger">*</span></label>
                        <input type="password" name="clave" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text text-danger">Ingresa un password de al menos 8 caracteres.</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Confirmnar Password<span class="text-danger">*</span></label>
                        <input type="password" name="reclave" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text text-danger">Confirma el password que has ingresado anteriormente.</div>
                    </div>
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-primary">Guardar</button>
                    <a href="<?php echo BASE_URL ?>" class="btn btn-link">Volver</a>
                </form>
            <?php else: ?>
                <p class="text-info">El password no se puede cambiar</p>
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