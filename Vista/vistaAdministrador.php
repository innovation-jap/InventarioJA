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
            --immersive-blue-black: #22404D;
            --resilient-turquoise: #00A0AF;
            --empowered-yellow: #E3E24F;
            --startup-white:#FFFFFF;
            --sustainable-green: #008B61;
            --ice: #C3E5F5; 
        }

        body {
            background-color: #f6f6f8;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--immersive-blue-black);
        }

        .sidebar {
            background: linear-gradient(180deg, var(--immersive-blue-black), #0c2028);
            width: 260px;
        }
        
        @media (max-width: 991.98px) {
            .sidebar { display: none !important; }
            main { padding-top: 70px !important; }
        }
        
        .sidebar .nav-link {
            color: var(--startup-white);
            border-radius: .5rem;
            margin-bottom: .4rem;
            transition: all 0.2s;
            padding: 0.75rem 1rem;
        }

        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background-color: var(--resilient-turquoise);
            color: var(--startup-white);
        }

        .badge-admin {
            background-color: var(--empowered-yellow) !important;
            color: var(--immersive-blue-black) !important;
        }
        
        .card { border-radius: 1rem; border: 1px solid rgba(0, 0, 0, 0.05); }

        .card-header {
            background-color: var(--ice);
            color: var(--immersive-blue-black);
            font-weight: 600;
            border-radius: 1rem 1rem 0 0 !important;
        }

        .btn-primary-brand {
            background-color: var(--resilient-turquoise);
            border-color: var(--resilient-turquoise);
            color: white;
        }

        .page-title {
            color: var(--immersive-blue-black);
            border-left: 6px solid var(--resilient-turquoise);
            padding-left: .75rem;
            font-weight: 700;
        }
    </style>
</head>

<?php
$seccion = $_GET['seccion'] ?? 'usuarios';

if (empty($_SESSION['esAdmin'])) {
    header("Location: ../index.php");
    exit();
}
// Variables para edición (asumiendo que vienen del controlador)
$idEdit = $idUsuario ?? null; 
?>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top d-lg-none">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="d-flex min-vh-100">
    <aside class="sidebar d-none d-lg-flex flex-column p-3 text-white vh-100 position-sticky top-0">
        <div class="mb-4 text-center">
            <h4 class="fw-bold">Donde Patty</h4>
            <span class="badge badge-admin">ADMINISTRADOR</span>
        </div>
        <nav class="flex-grow-1">
            <a href="controladorAdministrador.php?seccion=usuarios" class="nav-link <?= $seccion === 'usuarios' ? 'active' : '' ?>">
                <span class="material-icons-outlined me-2">group</span> Usuarios
            </a>
            <a href="controladorAdministrador.php?seccion=movimientos" class="nav-link <?= $seccion === 'movimientos' ? 'active' : '' ?>">
                <span class="material-icons-outlined me-2">swap_horiz</span> Movimientos
            </a>
        </nav>
        <hr>
        <a href="controladorCerrarSesion.php" class="btn btn-outline-light w-100">Cerrar sesión</a>
    </aside>

    <main class="flex-grow-1 p-4">
        <?php if ($seccion === 'usuarios'): ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title mb-0">Gestión de usuarios</h2>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <?= !empty($idEdit) ? "Editar Usuario" : "Registrar Nuevo Usuario" ?>
                </div>
                <div class="card-body">
                    <form method="POST" action="controladorAdministrador.php?seccion=usuarios">
                        <input type="hidden" name="idUsuario" value="<?= htmlspecialchars((string)($idEdit ?? '')) ?>">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label small fw-bold text-uppercase">Nombre</label>
                                <input required type="text" name="nombreU" value="<?= htmlspecialchars($nombreU ?? '') ?>" class="form-control" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label small fw-bold text-uppercase">Apellido</label>
                                <input required type="text" name="apellidoU" value="<?= htmlspecialchars($apellidoU ?? '') ?>" class="form-control" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label small fw-bold text-uppercase">Correo</label>
                                <input required type="email" name="correo" value="<?= htmlspecialchars($correo ?? '') ?>" class="form-control" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label small fw-bold text-uppercase">Pass <?= !empty($idEdit) ? '(Opcional)' : '' ?></label>
                                <input type="password" name="pass" class="form-control" <?= !empty($idEdit) ? '' : 'required' ?> />
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="<?= !empty($idEdit) ? 'update' : 'add' ?>" class="btn btn-primary-brand">
                                <?= !empty($idEdit) ? 'Actualizar Datos' : 'Registrar Usuario' ?>
                            </button>
                            <?php if(!empty($idEdit)): ?>
                                <a href="controladorAdministrador.php?seccion=usuarios" class="btn btn-secondary">Cancelar</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header">Usuarios registrados</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre Completo</th>
                                    <th>Correo</th>
                                    <th>Rol</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($usuarios)): ?>
                                <?php foreach ($usuarios as $u): ?>
                                    <tr>
                                        <td><small class="text-muted"><?= htmlspecialchars((string)$u['_id']) ?></small></td>
                                        <td><?= htmlspecialchars($u['nombreU'] . ' ' . $u['apellidoU']) ?></td>
                                        <td><?= htmlspecialchars($u['correo']) ?></td>
                                        <td>
                                            <span class="badge rounded-pill <?= !empty($u['esAdmin']) ? 'badge-admin' : 'bg-info text-dark' ?>">
                                                <?= !empty($u['esAdmin']) ? 'ADMINISTRADOR' : 'OPERADOR' ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="controladorAdministrador.php?seccion=usuarios&edit=<?= (string)$u['_id'] ?>" class="btn btn-sm btn-outline-info">
                                                <span class="material-icons-outlined" style="font-size:16px">edit</span>
                                            </a>
                                            <?php if ($u['correo'] !== 'admin@inventario.com'): ?>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar usuario?');">
                                                    <input type="hidden" name="idUsuario" value="<?= (string)$u['_id'] ?>">
                                                    <button type="submit" name="delete" class="btn btn-sm btn-outline-danger">
                                                        <span class="material-icons-outlined" style="font-size:16px">delete</span>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted small ms-2">Protegido</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php elseif ($seccion === 'movimientos'): ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title mb-0">Historial de movimientos</h2>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID Mov</th>
                                    <th>Usuario</th>
                                    <th>Producto</th>
                                    <th>Almacén</th>
                                    <th>Tipo</th>
                                    <th>Cant.</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($movimientos)): foreach ($movimientos as $m): ?>
                                    <tr>
                                        <td><strong><?= substr((string)$m['idMovimiento'], -6) ?></strong></td>
                                        <td><?= htmlspecialchars($m['nombreU'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($m['nombreP'] ?? 'N/A') ?></td>
                                        <td><span class="text-muted"><?= htmlspecialchars($m['almacen'] ?? 'N/A') ?></span></td>
                                        <td>
                                            <span class="badge <?= (strtoupper($m['tipo']) === 'ENTRADA') ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $m['tipo'] ?>
                                            </span>
                                        </td>
                                        <td><?= $m['cantidad'] ?></td>
                                        <td><?= is_object($m['fechaM']) ? $m['fechaM']->toDateTime()->format('d/m/Y H:i') : $m['fechaM'] ?></td>
                                    </tr>
                                <?php endforeach; endif; ?>
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