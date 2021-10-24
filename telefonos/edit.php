<?php
    require('../class/rutas.php');
    require('../class/conexion.php');
    require('../class/config.php');

    session_start();

    $title = 'Editar Teléfono';

    if (isset($_GET['telefono'])) {
        $id = (int) $_GET['telefono'];

        $res = $mbd->prepare("SELECT t.id, t.numero, t.fijo, t.telefonoable_id, t.telefonoable_type, f.nombre, u.id as usuario FROM funcionarios f INNER JOIN telefonos t ON t.telefonoable_id = f.id INNER JOIN usuarios u ON u.usuarioable_id = f.id WHERE t.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $telefono = $res->fetch();


        #proceso de validacion y recuperacion de datos desde el formulario
        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $numero = filter_var($_POST['numero'], FILTER_VALIDATE_INT);
            $fijo = filter_var($_POST['fijo'], FILTER_VALIDATE_INT);

            if (strlen($numero) < 9) {
            $msg = 'Ingrese el teléfono del funcionario, al menos 9 dígitos';
            }elseif (!$fijo) {
                $msg = 'Seleccione el tipo de teléfono';
            }else{
                $res = $mbd->prepare("UPDATE telefonos SET numero = ?, fijo = ? WHERE id = ?");
                $res->bindParam(1, $numero);
                $res->bindParam(2, $fijo);
                $res->bindParam(3, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El teléfono se ha modificado correctamente';
                    header('Location: ' . SHOW_TELEFONO . $id);
                }
            }

            #print_r($nombre);exit;
        }
     }
    /* echo '<pre>';
    print_r($regiones);exit;
    echo '</pre>'; */
?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_id'] == $telefono['usuario']): ?>
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
            <?php if($telefono): ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono<span class="text-danger">*</span></label>
                        <input type="number" name="numero" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $telefono['numero'] ?>">
                        <div id="emailHelp" class="form-text text-danger">Ingresa el teléfono que deseas modificar.</div>
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo<span class="text-danger">*</span></label>
                        <select name="fijo" class="form-control">
                            <option value="<?php echo $telefono['fijo']; ?>">
                                <?php if($telefono['fijo'] == 1): ?>
                                    Teléfono Fijo
                                <?php else: ?>
                                    Teléfono Móvil
                                <?php endif; ?>
                            </option>
                            <option value="">Seleccione...</option>
                            <option value="1">Fijo</option>
                            <option value="2">Movil</option>

                        </select>
                        <div id="emailHelp" class="form-text text-danger">Selecciona el tipo de teléfono que deseas registrar.</div>
                    </div>
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-primary">Editar</button>
                    <a href="<?php echo SHOW_TELEFONO . $id; ?>" class="btn btn-link">Volver</a>
                </form>
            <?php else: ?>
                <p class="text-info">No se puede modificar el teléfono</p>
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
