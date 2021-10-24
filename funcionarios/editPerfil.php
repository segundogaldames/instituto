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

        #consultar en la tabla funcionarios si hay un funcionario con este id
        $res = $mbd->prepare("SELECT f.id, f.rut, f.nombre, f.fecha_nacimiento, f.email, f.direccion, f.rol_id, f.comuna_id, r.nombre as rol, c.nombre as comuna FROM funcionarios f INNER JOIN roles r ON f.rol_id = r.id INNER JOIN comunas c ON c.id = f.comuna_id WHERE f.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $funcionario = $res->fetch();

        $res = $mbd->prepare("SELECT id, activo FROM usuarios WHERE usuarioable_id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $usuario = $res->fetch();

        #lista de roles
        $res = $mbd->query("SELECT id, nombre FROM roles ORDER BY nombre");
        $roles = $res->fetchall();

        #lista comunas
        $res = $mbd->query("SELECT id, nombre FROM comunas ORDER BY nombre");
        $comunas = $res->fetchall();

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $fecha_nacimiento = trim(strip_tags($_POST['fecha_nacimiento']));
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $direccion = trim(strip_tags($_POST['direccion']));
            $comuna = filter_var($_POST['comuna'], FILTER_VALIDATE_INT);

            if (!$fecha_nacimiento) {
                $msg = 'Debe ingresar la fecha de nacimiento del funcionario';
            }elseif (!$email) {
                $msg = 'Debe ingresar el email del funcionario';
            }elseif (!$direccion) {
                $msg = 'Debe ingresar la dirección del funcionario';
            }elseif (!$comuna) {
                $msg = 'Debe ingresar la comuna del funcionario';
            }else {
                #editar o modificar el funcionario segun el id enviado desde show
                $res = $mbd->prepare("UPDATE funcionarios SET fecha_nacimiento = ?, email = ?, direccion = ?, comuna_id = ? WHERE id = ?");
                $res->bindParam(1, $fecha_nacimiento);
                $res->bindParam(2, $email);
                $res->bindParam(3, $direccion);
                $res->bindParam(4, $comuna);
                $res->bindParam(5, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'Su perfil se ha modificado correctamente';
                    header('Location: ' . FUNCIONARIOS . 'show.php?id=' . $id);
                }
            }
        }

        //print_r($region);
    }

    // echo '<pre>';
    // print_r($regiones);exit;
    // echo '</pre>';
?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador(a)' || $_SESSION['usuario_id'] == $usuario['id']): ?>

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
            <h3 class="text-primary">Editar Funcionario</h3>
            <p>Campos obligatorios <span class="text-danger">*</span></p>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($funcionario)): ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Fecha de nacimiento<span class="text-danger">*</span></label>
                        <input type="date" name="fecha_nacimiento" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $funcionario['fecha_nacimiento']; ?>">
                        <div id="emailHelp" class="form-text text-danger">Ingresa la fecha de nacimiento que deseas modificar.</div>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Email<span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $funcionario['email']; ?>">
                        <div id="emailHelp" class="form-text text-danger">Ingresa el email que deseas modificar.</div>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Dirección<span class="text-danger">*</span></label>
                        <input type="text" name="direccion" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $funcionario['direccion']; ?>">
                        <div id="emailHelp" class="form-text text-danger">Ingresa la dirección que deseas modificar.</div>
                    </div>
                     <div class="mb-3">
                        <label for="nombre" class="form-label">Comuna<span class="text-danger">*</span></label>
                        <select name="comuna" class="form-control" id="">
                            <option value="<?php echo $funcionario['comuna_id']; ?>">
                                <?php echo $funcionario['comuna'] ?>
                            </option>

                            <option value="">Seleccione...</option>

                            <?php foreach($comunas as $comuna): ?>
                                <option value="<?php echo $comuna['id']; ?>">
                                    <?php echo $comuna['nombre']; ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                        <div id="emailHelp" class="form-text text-danger">Selecciona el comuna que deseas modificar.</div>
                    </div>

                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-primary">Editar</button>
                    <a href="<?php echo FUNCIONARIOS . 'show.php?id=' . $id; ?>" class="btn btn-link">Volver</a>
                </form>

            <?php else: ?>
                <p class="text-info">El dato no existe</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
<?php else: ?>
    <?php header('Location:' . BASE_URL); ?>
<?php endif; ?>