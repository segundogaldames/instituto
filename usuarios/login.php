<?php
    require('../class/rutas.php');
    require('../class/conexion.php');
    require('../class/config.php');

    session_start();

    $title = 'Iniciar Sesion';

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $clave = trim(strip_tags($_POST['clave']));

        //print_r($_POST);exit;

        if (!$email) {
            $msg = 'Ingresa tu correo electrónico';
        }elseif (!$clave) {
            $msg = 'Ingresa tu password';
        }else {
            #consultar por el usuario segun email y clave ingresados
            $clave = sha1($clave);

            $res = $mbd->prepare("SELECT u.id, u.email, u.usuarioable_type, u.usuarioable_id, f.nombre FROM usuarios u INNER JOIN funcionarios f ON f.id = u.usuarioable_id WHERE u.email = ? AND u.clave = ? AND activo = 1");
            $res->bindParam(1, $email);
            $res->bindParam(2, $clave);
            $res->execute();
            $usuario = $res->fetch();

            if (!$usuario) {
                $msg = 'El email o el password no están registrados';
            }else {
                $_SESSION['autenticado'] = true;
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_type'] = $usuario['usuarioable_type'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];

                if ($usuario['usuarioable_type'] == 'Funcionario') {
                    #preguntamos por el rol
                    $res = $mbd->prepare("SELECT r.id, r.nombre as rol FROM roles r INNER JOIN funcionarios f ON f.rol_id = r.id WHERE f.id = ?");
                    $res->bindParam(1, $usuario['usuarioable_id']);
                    $res->execute();
                    $rol = $res->fetch();

                    if ($rol) {
                        $_SESSION['usuario_rol'] = $rol['rol'];
                    }
                }

                header('Location: ' . BASE_URL);
            }
        }
    }


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

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text text-danger">Ingresa tu email.</div>
                </div>
                    <div class="mb-3">
                    <label for="email" class="form-label">Password<span class="text-danger">*</span></label>
                    <input type="password" name="clave" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text text-danger">Ingresa tu password .</div>
                    </div>

                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-primary">Ingresar</button>
                </form>
        </div>

    </div>

</body>
</html>
