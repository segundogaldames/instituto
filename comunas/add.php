<?php
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    if (isset($_GET['region'])) {
       $region_id = (int) $_GET['region'];

       #verificar que existe la region segun la variable region que se ha enviado desde el show de regiones
       $res = $mbd->prepare("SELECT id, nombre FROM regiones WHERE id = ?");
       $res->bindParam(1, $region_id);
       $res->execute();
       $region = $res->fetch();

       if(isset($_POST['confirm']) && $_POST['confirm'] == 1){
            $nombre = trim(strip_tags($_POST['nombre']));

            if (!$nombre) {
                $msg = 'Ingrese el nombre de la comuna';
            }else {
                #verificar que la comuna no exista en la tabla comunas
                $res = $mbd->prepare("SELECT id FROM comunas WHERE nombre = ?");
                $res->bindParam(1, $nombre);
                $res->execute();
                $comuna = $res->fetch();

                if ($comuna) {
                    $msg = 'La comuna ingresada ya existe... intente con otra';
                }else {
                    #registramos la comuna
                    $res = $mbd->prepare("INSERT INTO comunas(nombre, region_id) VALUES(?, ?)");
                    $res->bindParam(1, $nombre);
                    $res->bindParam(2, $region_id);
                    $res->execute();

                    $row = $res->rowCount();

                    if ($row) {
                        $_SESSION['success'] = 'La comuna se ha registrado correctamente';
                        header('Location: ' . REGIONES . 'show.php?id=' . $region_id);
                    }
                }
            }
       }
    }

?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador(a)'): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regiones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h3 class="text-primary">Nueva Comuna</h3>
            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($region)): ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Región: <?php echo $region['nombre']; ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Comuna</label>
                        <input type="text" name="nombre" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php if(isset($_POST['nombre'])) echo $_POST['nombre']; ?>">
                        <div id="emailHelp" class="form-text">Ingresa el nombre de la comuna que deseas registrar.</div>
                    </div>
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-primary">Guardar</button>
                    <a href="<?php echo REGIONES . 'show.php?id=' . $region_id; ?>" class="btn btn-link">Volver</a>
                </form>
            <?php else: ?>
                <p class="text-info">UPS!! La comuna no puede registrarse</p>
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
