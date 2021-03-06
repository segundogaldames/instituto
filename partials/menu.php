<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Instituto</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL; ?>">Inicio</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Contacto</a>
                </li>
                <?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador(a)'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Administrar
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?php echo REGIONES; ?>">Regiones</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo FUNCIONARIOS; ?>">Funcionarios</a></li>
                            <li><a class="dropdown-item" href="<?php echo ROLES; ?>">Roles</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (isset($_SESSION['autenticado'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['usuario_nombre']; ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="<?php echo FUNCIONARIOS . 'show.php?id=' . $_SESSION['usuario_funcionarioId']; ?>">Mi Perfil</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo EDIT_PASS . $_SESSION['usuario_id']; ?>">Cambiar Password</a>
                            </li>
                            <li>
                                <li><a class="dropdown-item" href="<?php echo LOGOUT; ?>">Cerrar Sesi??n</a></li>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo LOGIN; ?>">Iniciar Sesi??n</a>
                    </li>
                <?php endif; ?>
            </ul>
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Encuentra tu carrera" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>