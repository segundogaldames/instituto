<?php
    require('../class/rutas.php');
    require('../class/conexion.php');
    require('../class/config.php');

    session_start();

    $title = 'Nuevo Teléfono';

    if (isset($_GET['funcionario'])) {
        $id_funcionario = (int) $_GET['funcionario'];

        $res = $mbd->prepare("SELECT id, nombre FROM funcionarios WHERE id = ?");
        $res->bindParam(1, $id_funcionario);
        $res->execute();
        $funcionario = $res->fetch();


        #proceso de validacion y recuperacion de datos desde el formulario
        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $numero = filter_var($_POST['numero'], FILTER_VALIDATE_INT);

            if (!$numero) {
            $msg = 'Ingrese el teléfono del funcionario';
            }else{
                #preguntar si el rol ingresado ya existe en la tabla roles
                $res = $mbd->prepare("SELECT id FROM telefonos WHERE numero = ? AND telefonoable_type = 'Funcionario'");
                $res->bindParam(1, $numero);
                $res->execute();
                $telefono = $res->fetch();

                if ($telefono) {
                    $msg = 'El teléfono ingresado ya existe... intente con otro';
                }else{
                    #guardamos el telefono
                    $res = $mbd->prepare("INSERT INTO telefonos(numero, fijo, telefonoable_id, telefonoable_type) VALUES(?, ?, ?, 'Funcionario')");
                    $res->bindParam(1, $numero);
                    $res->bindParam(2, $fijo);
                    $res->bindParam(3, $id_funcionario);
                    $res->execute();

                    $row = $res->rowCount();

                    if ($row) {
                        $_SESSION['success'] = 'El teléfono se ha registrado correctamente';
                        header('Location: ' . FUNCIONARIOS . 'show.php?id=' . $id_funcionario);
                    }
                }
            }

            #print_r($nombre);exit;
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
    <title><?php echo TITLE . $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h3 class="text-primary"><?php echo $title; ?></h3>
            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>
            <?php if($funcionario): ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono<span class="text-danger">*</span></label>
                        <input type="number" name="numero" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php if(isset($_POST['numero'])) echo $_POST['numero']; ?>">
                        <div id="emailHelp" class="form-text text-danger">Ingresa el teléfono que deseas registrar.</div>
                    </div>
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-primary">Guardar</button>
                    <a href="<?php echo ROLES; ?>" class="btn btn-link">Volver</a>
                </form>
            <?php else: ?>
                <p class="text-info">No se puede registrar el teléfono</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
