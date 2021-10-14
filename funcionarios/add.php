<?php
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    #lista de roles
    $res = $mbd->query("SELECT id, nombre FROM roles ORDER BY nombre");
    $roles = $res->fetchall();

    #lista comunas
    $res = $mbd->query("SELECT id, nombre FROM comunas ORDER BY nombre");
    $comunas = $res->fetchall();

    #proceso de validacion y recuperacion de datos desde el formulario
    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        $rut = trim(strip_tags($_POST['rut']));
        $nombre = trim(strip_tags($_POST['nombre']));
        $fecha_nacimiento = trim(strip_tags($_POST['fecha_nacimiento']));
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $direccion = trim(strip_tags($_POST['direccion']));
        $rol = filter_var($_POST['rol'], FILTER_VALIDATE_INT);
        $comuna = filter_var($_POST['comuna'], FILTER_VALIDATE_INT);

        if (!$rut) {
            $msg = 'Debe ingresar el rut del funcionario';
        }elseif (!$nombre) {
            $msg = 'Debe ingresar el nombre del funcionario';
        }elseif (!$fecha_nacimiento) {
            $msg = 'Debe ingresar la fecha de nacimiento del funcionario';
        }elseif (!$email) {
            $msg = 'Debe ingresar el email del funcionario';
        }elseif (!$direccion) {
            $msg = 'Debe ingresar la dirección del funcionario';
        }elseif (!$comuna) {
            $msg = 'Debe ingresar la comuna del funcionario';
        }elseif (!$rol) {
            $msg = 'Debe ingresar el rol del funcionario';
        }else{
            #preguntar si el funcionario ingresado ya existe en la tabla funcionarios
            $res = $mbd->prepare("SELECT id FROM funcionarios WHERE rut = ? AND email = ?");
            $res->bindParam(1, $rut);
            $res->bindParam(2, $email);
            $res->execute();
            $funcionario = $res->fetch();

            if ($funcionario) {
                $msg = 'El funcionario ingresado ya existe... intente con otro';
            }else{
                #guardamos el funcionario
                $res = $mbd->prepare("INSERT INTO funcionarios(rut, nombre, fecha_nacimiento, email, direccion, rol_id, comuna_id) VALUES(?, ?, ?, ?, ?, ?, ?)");
                $res->bindParam(1, $rut);
                $res->bindParam(2, $nombre);
                $res->bindParam(3, $fecha_nacimiento);
                $res->bindParam(4, $email);
                $res->bindParam(5, $direccion);
                $res->bindParam(6, $rol);
                $res->bindParam(7, $comuna);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El funcionario se ha registrado correctamente';
                    header('Location: ' . FUNCIONARIOS);
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
    <title>Funcionarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-6 offset-md-3 mb-3">
            <h3 class="text-primary">Nuevo Funcionario</h3>
            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">RUT<span class="text-danger">*</span></label>
                    <input type="text" name="rut" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php if(isset($_POST['rut'])) echo $_POST['rut']; ?>">
                    <div id="emailHelp" class="form-text text-danger">Ingresa el rut que deseas modificar.</div>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre<span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php if(isset($_POST['nombre'])) echo $_POST['nombre']; ?>">
                    <div id="emailHelp" class="form-text text-danger">Ingresa el nombre que deseas modificar.</div>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Fecha de nacimiento<span class="text-danger">*</span></label>
                    <input type="date" name="fecha_nacimiento" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php if(isset($_POST['fecha_nacimiento'])) echo $_POST['fecha_nacimiento']; ?>">
                    <div id="emailHelp" class="form-text text-danger">Ingresa la fecha de nacimiento que deseas modificar.</div>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Email<span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
                    <div id="emailHelp" class="form-text text-danger">Ingresa el email que deseas modificar.</div>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Dirección<span class="text-danger">*</span></label>
                    <input type="text" name="direccion" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php if(isset($_POST['direccion'])) echo $_POST['direccion']; ?>">
                    <div id="emailHelp" class="form-text text-danger">Ingresa la dirección que deseas modificar.</div>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Comuna<span class="text-danger">*</span></label>
                    <select name="comuna" class="form-control" id="">

                        <option value="">Seleccione...</option>

                        <?php foreach($comunas as $comuna): ?>
                            <option value="<?php echo $comuna['id']; ?>">
                                <?php echo $comuna['nombre']; ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                    <div id="emailHelp" class="form-text text-danger">Selecciona el comuna que deseas modificar.</div>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Rol<span class="text-danger">*</span></label>
                    <select name="rol" class="form-control" id="">

                        <option value="">Seleccione...</option>

                        <?php foreach($roles as $rol): ?>
                            <option value="<?php echo $rol['id']; ?>">
                                <?php echo $rol['nombre']; ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                        <div id="emailHelp" class="form-text text-danger">Selecciona el rol que deseas modificar.</div>
                </div>

                <input type="hidden" name="confirm" value="1">
                <button type="submit" class="btn btn-outline-primary">Guardar</button>
                <a href="<?php echo FUNCIONARIOS; ?>" class="btn btn-link">Volver</a>
            </form>
        </div>

    </div>

</body>
</html>
