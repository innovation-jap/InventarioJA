<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Donde Patty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        :root {
            --immersive-blue-black: #22404D; /* fondo y texto principal */
            --resilient-turquoise: #00A0AF;/* botones primarios */
            --empowered-yellow: #E3E24F; /* acentos/admin badge */
            --startup-white:#FFFFFF;
            --sustainable-green: #008B61; /* estados OK */
            --ice: #C3E5F5; 
        }

        body {
            background-color: #f6f6f8;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--immersive-blue-black); /* Color de texto base */
        }

        /* ---------------------------------- */
        /* Sidebar (Para PC/Tablet) */
        /* ---------------------------------- */
        .sidebar {
            background: linear-gradient(180deg, var(--immersive-blue-black), #0c2028);
            width: 260px; /* Ancho fijo para PC */
        }
        
        /* Ocultar el Sidebar en móvil/tablet y mostrar el Offcanvas */
        @media (max-width: 991.98px) {
            .sidebar {
                display: none !important; 
            }
            /* Padding para que el contenido no quede debajo del Nav-Top en móvil */
            main {
                padding-top: 70px !important; 
            }
        }
        
        /* Sidebar Link Styles */
        .sidebar .nav-link {
            color: var(--startup-white);
            border-radius: .5rem;
            margin-bottom: .4rem;
            transition: all 0.2s;
            padding: 0.75rem 1rem;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: var(--resilient-turquoise);
            color: var(--startup-white);
        }

        /* ---------------------------------- */
        /* Main Content Styles */
        /* ---------------------------------- */
        .badge-admin {
            background-color: var(--empowered-yellow) !important;
            color: var(--immersive-blue-black) !important;
        }
        
        .card {
            border-radius: 1rem;
            border: 1px solid rgba(0, 0, 0, 0.05); /* Sombra más suave */
        }

        .card-header {
            border-bottom: 0;
            background-color: var(--ice); /* Color de fondo plano para Card Header */
            color: var(--immersive-blue-black);
            font-weight: 600;
            border-radius: 1rem 1rem 0 0 !important; /* Bordes redondeados superiores */
        }

        .btn-primary-brand {
            background-color: var(--resilient-turquoise);
            border-color: var(--resilient-turquoise);
            transition: all 0.2s;
        }

        .btn-primary-brand:hover {
            background-color: var(--immersive-blue-black);
            border-color: var(--immersive-blue-black);
        }

        .page-title {
            color: var(--immersive-blue-black);
            border-left: 6px solid var(--resilient-turquoise); /* Usamos Turquoise para el acento */
            padding-left: .75rem;
            font-weight: 700;
        }
        
        .table thead th {
            font-size: 0.85rem;
            color: var(--immersive-blue-black);
            border-bottom: 2px solid var(--ice); /* Separador más notorio */
        }
        
        .table tbody tr:hover {
            background-color: #eaf8ff; /* Light hover effect */
        }
        
        /* Mejorar la alineación vertical de la tabla */
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>

<?php
// Lógica PHP sin cambios
$seccion = $_GET['seccion'] ?? 'usuarios';

if (empty($_SESSION['esAdmin'])) {
    header("Location: ../index.php");
    exit();
}
// Aquí asumo que las variables $idUsuario, $nombreU, $apellidoU, $correo, $usuarios, $movimientos están cargadas
// ... (Tu código PHP de carga de datos aquí) ...
?>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top d-lg-none">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav" aria-controls="offcanvasNav">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="d-flex min-vh-100">

    <aside class="sidebar d-none d-lg-flex flex-column p-3 text-white vh-100 position-sticky top-0" style="width: 260px;">
        <div class="mb-4">
            <h5 class="mb-1 text-uppercase small text-white-50">Panel</h5>
            <h4 class="mb-1 fw-bold">Administrador</h4>
        </div>

        <nav class="flex-grow-1">
            <a href="../Controlador/controladorAdministrador.php?seccion=usuarios"
               class="nav-link d-block <?= $seccion === 'usuarios' ? 'active' : '' ?>">
               <span class="material-icons-outlined me-2" style="font-size: 20px;">group</span> Usuarios
            </a>
            <a href="../Controlador/controladorAdministrador.php?seccion=movimientos"
               class="nav-link d-block <?= $seccion === 'movimientos' ? 'active' : '' ?>">
               <span class="material-icons-outlined me-2" style="font-size: 20px;">swap_horiz</span> Movimientos
            </a>
        </nav>

        <hr class="text-white mt-auto">
        <a href="../Controlador/controladorCerrarSesion.php" class="btn btn-outline-light w-100">
            <span class="material-icons-outlined me-2" style="font-size: 20px;">logout</span> Cerrar sesión
        </a>
    </aside>
    
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="offcanvasNav" aria-labelledby="offcanvasNavLabel">
      <div class="offcanvas-header" style="background-color: var(--immersive-blue-black);">
        <h5 class="offcanvas-title fw-bold" id="offcanvasNavLabel">ADMINISTRADOR</h5>
        <button type="button" class="btn-close text-reset btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body sidebar">
        <nav class="flex-grow-1">
            <a href="../Controlador/controladorAdministrador.php?seccion=usuarios"
               class="nav-link d-block <?= $seccion === 'usuarios' ? 'active' : '' ?>" data-bs-dismiss="offcanvas">
               <span class="material-icons-outlined me-2" style="font-size: 20px;">group</span> Usuarios
            </a>
            <a href="../Controlador/controladorAdministrador.php?seccion=movimientos"
               class="nav-link d-block <?= $seccion === 'movimientos' ? 'active' : '' ?>" data-bs-dismiss="offcanvas">
               <span class="material-icons-outlined me-2" style="font-size: 20px;">swap_horiz</span> Movimientos
            </a>
        </nav>

        <hr class="text-white mt-auto">
        <a href="../Controlador/controladorCerrarSesion.php" class="btn btn-outline-light w-100 mt-3">
            <span class="material-icons-outlined me-2" style="font-size: 20px;">logout</span> Cerrar sesión
        </a>
      </div>
    </div>


    <main class="flex-grow-1 p-4">
        <?php if ($seccion === 'usuarios'): ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title mb-0">Gestión de usuarios</h2>
            </div>

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-truncate">
                        <?= !empty($idUsuario)
                            ? "Editar usuario ID: " . htmlspecialchars((string)$idUsuario)
                            : "Registrar nuevo usuario" ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="controladorAdministrador.php?seccion=usuarios">
                        <input type="hidden" name="idUsuario" value="<?= htmlspecialchars((string)($idUsuario ?? '')) ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-uppercase">Nombre</label>
                                <input required type="text" name="nombreU"
                                       value="<?= htmlspecialchars($nombreU ?? '') ?>"
                                       class="form-control" placeholder="Nombre del usuario" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-uppercase">Apellido</label>
                                <input required type="text" name="apellidoU"
                                       value="<?= htmlspecialchars($apellidoU ?? '') ?>"
                                       class="form-control" placeholder="Apellido del usuario" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-uppercase">Correo</label>
                                <input required type="email" name="correo"
                                       value="<?= htmlspecialchars($correo ?? '') ?>"
                                       class="form-control" placeholder="correo@ejemplo.com" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-uppercase">
                                    Contraseña <?= !empty($idUsuario) ? '(dejar vacío para mantener)' : '' ?>
                                </label>
                                <input type="<?= !empty($idUsuario) ? 'text' : 'password' ?>" name="pass"
                                       class="form-control" placeholder="Mínimo 6 caracteres"
                                       <?= !empty($idUsuario) ? '' : 'required' ?> />
                            </div>
                        </div>

                        <?php if (!empty($idUsuario)): ?>
                            <div class="d-flex gap-2 pt-2">
                                <button type="submit" name="update" class="btn btn-primary-brand">
                                    <span class="material-icons-outlined me-1" style="font-size: 20px;">update</span>
                                    Actualizar
                                </button>
                                <a href="controladorAdministrador.php?seccion=usuarios" class="btn btn-outline-secondary">
                                    <span class="material-icons-outlined me-1" style="font-size: 20px;">cancel</span>
                                    Cancelar
                                </a>
                            </div>
                        <?php else: ?>
                            <button type="submit" name="add" class="btn btn-primary-brand mt-2">
                                <span class="material-icons-outlined me-1" style="font-size: 20px;">person_add</span>
                                Registrar usuario
                            </button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h5 class="mb-0 text-truncate">
                        Usuarios registrados (<?= is_array($usuarios) ? count($usuarios) : 0 ?>)
                    </h5>
                    <?php
                    $totalAdmins = 0;
                    if (!empty($usuarios)) {
                        foreach ($usuarios as $u) {
                            if (!empty($u['esAdmin'])) {
                                $totalAdmins++;
                            }
                        }
                    }
                    ?>
                    <span class="badge badge-admin py-2 px-3 fw-bold"><?= $totalAdmins ?> Admins</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 align-middle">
                            <thead style="background-color: var(--ice);">
                            <tr>
                                <th class="py-3">ID</th>
                                <th class="py-3">Nombre Completo</th>
                                <th class="py-3">Correo</th>
                                <th class="py-3">Rol</th>
                                <th class="text-end py-3">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($usuarios)): ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars((string)($usuario['_id'] ?? '')) ?></strong></td>
                                        <td><?= htmlspecialchars(($usuario['nombreU'] ?? '') . ' ' . ($usuario['apellidoU'] ?? '')) ?></td>
                                        <td><?= htmlspecialchars($usuario['correo'] ?? '') ?></td>
                                        <td>
                                            <?php $esAdmin = !empty($usuario['esAdmin']); ?>
                                            <span class="badge rounded-pill <?= $esAdmin ? 'badge-admin' : 'bg-info text-dark' ?>">
                                                <?= $esAdmin ? 'ADMINISTRADOR' : 'OPERADOR' ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <?php if (!$esAdmin): ?>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="controladorAdministrador.php?seccion=usuarios&edit=<?= (string)$usuario['_id'] ?>" class="btn btn-outline-info" title="Editar">
                                                        <span class="material-icons-outlined" style="font-size: 18px;">edit</span>
                                                    </a>
                                                    <form method="POST" class="d-inline"
                                                          onsubmit="return confirm('¿Eliminar a <?= htmlspecialchars($usuario['nombreU'] ?? 'este usuario') ?>?');">
                                                        <input type="hidden" name="idUsuario" value="<?= (string)$usuario['_id'] ?>">
                                                        <button type="submit" name="delete" class="btn btn-outline-danger" title="Eliminar">
                                                            <span class="material-icons-outlined" style="font-size: 18px;">delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">Rol protegido</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">No hay usuarios registrados</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php elseif ($seccion === 'movimientos'): ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title mb-0">Movimientos de inventario por usuario</h2>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="mb-0 text-truncate">Historial de movimientos detallado</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 align-middle">
                            <thead style="background-color: var(--ice);">
                            <tr>
                                <th class="py-3">ID Mov</th>
                                <th class="py-3">Usuario</th>
                                <th class="py-3">Producto</th>
                                <th class="py-3">Tipo</th>
                                <th class="py-3">Cantidad</th>
                                <th class="py-3">Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($movimientos)): ?>
                                <?php foreach ($movimientos as $m): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars((string)($m['idMovimiento'] ?? '')) ?></strong></td>
                                        <td><?= htmlspecialchars($m['nombreU'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($m['nombreP'] ?? 'N/A') ?></td>
                                        <td>
                                            <?php
                                                $tipo = htmlspecialchars($m['tipo'] ?? 'N/A');
                                                $badgeClass = (strtoupper($tipo) === 'ENTRADA') ? 'bg-success' : 'bg-danger';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= $tipo ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($m['cantidad'] ?? 0) ?></td>
                                        <td>
                                            <?php 
                                            // Manejo de fecha si viene como objeto UTCDateTime
                                            if (isset($m['fechaM']) && is_object($m['fechaM'])) {
                                                echo $m['fechaM']->toDateTime()->format('Y-m-d H:i:s');
                                            } else {
                                                echo htmlspecialchars($m['fechaM'] ?? 'N/A');
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">No hay movimientos registrados</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>