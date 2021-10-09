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

        #consultar en la tabla regiones si hay una region con este id
        $res = $mbd->prepare("SELECT c.id, c.nombre, c.region_id, r.nombre as region FROM regiones r INNER JOIN comunas c ON c.region_id = r.id WHERE c.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $comuna = $res->fetch();

        #lista de regiones
        $res = $mbd->query("SELECT id, nombre FROM regiones ORDER BY nombre");
        $regiones = $res->fetchall();

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $nombre = trim(strip_tags($_POST['nombre']));
            $region = filter_var($_POST['region'], FILTER_VALIDATE_INT);

            if (!$nombre) {
                $msg = 'Ingrese el nombre de la comuna';
            }elseif (!$region) {
                $msg = 'Seleccione la región';
            }else {
                #modificamos la comuna
                $res = $mbd->prepare("UPDATE comunas SET nombre = ?, region_id = ? WHERE id = ?");
                $res->bindParam(1, $nombre);
                $res->bindParam(2, $region);
                $res->bindParam(3, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'La comuna se ha modificado correctamente';
                    header('Location: ' . COMUNAS . 'show.php?id=' . $id);
                }
            }
        }

        //print_r($region);
    }

    // echo '<pre>';
    // print_r($regiones);exit;
    // echo '</pre>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h3 class="text-primary">Editar Comuna</h3>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($comuna)): ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Comuna</label>
                        <input type="text" name="nombre" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $comuna['nombre']; ?>">
                        <div id="emailHelp" class="form-text">Ingresa la comuna que deseas modificar.</div>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Región</label>
                        <select name="region" class="form-control" id="">
                            <option value="<?php echo $comuna['region_id']; ?>">
                                <?php echo $comuna['region'] ?>
                            </option>

                            <option value="">Seleccione...</option>

                            <?php foreach($regiones as $region): ?>
                                <option value="<?php echo $region['id']; ?>">
                                    <?php echo $region['nombre']; ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                        <div id="emailHelp" class="form-text">Selecciona la región que deseas modificar.</div>
                    </div>
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-primary">Editar</button>
                    <a href="<?php echo REGIONES . 'show.php?id=' . $id; ?>" class="btn btn-link">Volver</a>
                </form>

            <?php else: ?>
                <p class="text-info">El dato no existe</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>