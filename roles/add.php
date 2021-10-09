<?php
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    #proceso de validacion y recuperacion de datos desde el formulario
    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        $nombre = trim(strip_tags($_POST['nombre']));

        if (!$nombre) {
           $msg = 'Ingrese el nombre del rol';
        }else{
            #preguntar si el rol ingresado ya existe en la tabla roles
            $res = $mbd->prepare("SELECT id FROM roles WHERE nombre = ?");
            $res->bindParam(1, $nombre);
            $res->execute();
            $rol = $res->fetch();

            if ($rol) {
                $msg = 'El rol ingresado ya existe... intente con otro';
            }else{
                #guardamos el rol
                $res = $mbd->prepare("INSERT INTO roles(nombre) VALUES(?)");
                $res->bindParam(1, $nombre);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El rol se ha registrado correctamente';
                    header('Location: ' . ROLES);
                }
            }
        }

        #print_r($nombre);exit;
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
    <title>Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h3 class="text-primary">Nuevo Rol</h3>
            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Rol</label>
                    <input type="text" name="nombre" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php if(isset($_POST['nombre'])) echo $_POST['nombre']; ?>">
                    <div id="emailHelp" class="form-text">Ingresa el rol que deseas registrar.</div>
                </div>
                <input type="hidden" name="confirm" value="1">
                <button type="submit" class="btn btn-outline-primary">Guardar</button>
                <a href="<?php echo ROLES; ?>" class="btn btn-link">Volver</a>
            </form>
        </div>

    </div>

</body>
</html>
