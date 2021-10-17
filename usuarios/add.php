<?php
    require('../class/rutas.php');
    require('../class/conexion.php');
    require('../class/config.php');

    session_start();

    $title = 'Nuevo Usuario';


    if (isset($_GET['funcionario'])) {
        $funcionario_id = (int) $_GET['funcionario'];

        #verificamos que el funcionario exista
        $res = $mbd->prepare("SELECT id, rut, nombre FROM funcionarios WHERE id = ?");
        $res->bindParam(1, $funcionario_id);
        $res->execute();
        $funcionario = $res->fetch();


        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
           $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
           $clave = trim(strip_tags($_POST['clave']));
           $reclave = trim(strip_tags($_POST['reclave']));

           if (!$email) {
               $msg = 'Ingrese el email de la cuenta';
           }elseif (strlen($clave) < 8) {
              $msg = 'El password debe tener al menos 8 caracteres';
           }elseif ($reclave != $clave) {
               $msg = 'Los passwords ingresados no coinciden';
           }else{
                #verificamos que el funcionario ingresado no tenga una cuenta
                $res = $mbd->prepare("SELECT id FROM usuarios WHERE email = ? AND usuarioable_id = ? AND usuarioable_type = 'Funcionario'");
                $res->bindParam(1, $email);
                $res->bindParam(2, $funcionario_id);
                $res->execute();
                $usuario = $res->fetch();

                if ($usuario) {
                    $_SESSION['danger'] = 'Este funcionario ya tiene una cuenta creada... intente con otro';
                    header('Location: ' . FUNCIONARIOS . 'show.php?id=' . $funcionario_id);
                }else {
                    #creamos la cuenta de usuario
                    $clave = sha1($clave);
                    $usuario_type = 'Funcionario';

                    $res = $mbd->prepare("INSERT INTO usuarios(email, clave, activo, usuarioable_id, usuarioable_type) VALUES(?, ?, 1, ?, ?)");
                    $res->bindParam(1, $email);
                    $res->bindParam(2, $clave);
                    $res->bindParam(3, $funcionario_id);
                    $res->bindParam(4, $usuario_type);;
                    $res->execute();

                    $row = $res->rowCount();

                    if ($row) {
                        $_SESSION['success'] = 'La cuenta de usuario se ha creado correctamente';
                        header('Location: ' . FUNCIONARIOS . 'show.php?id=' . $funcionario_id);
                    }
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

            <?php if(!empty($funcionario)): ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">RUT: <?php echo $funcionario['rut']; ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Nombre: <?php echo $funcionario['nombre']; ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
                        <div id="emailHelp" class="form-text text-danger">Ingresa el email que deseas registrar.</div>
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
                    <a href="<?php echo FUNCIONARIOS . 'show.php?id=' . $funcionario_id; ?>" class="btn btn-link">Volver</a>
                </form>
            <?php else: ?>
                <p class="text-info">La cuenta no se puede crear</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
