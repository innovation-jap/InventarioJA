<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

// CAMBIO: El ID de edición viene como string (ObjectId)
$editingId = isset($_GET['editar']) ? $_GET['editar'] : null;
$nombreUsuario = htmlspecialchars($_SESSION['nombreU'] ?? 'Usuario');
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .table-row-form-edit { display: contents; }
        .table-row-form-edit td input { padding: 0.25rem 0.5rem; height: 36px; font-size: 0.875rem; }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-immersive-blue-black dark:text-startup-white min-h-screen">
<div class="flex min-h-screen w-full justify-center">
    <div class="flex flex-col max-w-[1200px] w-full px-4 sm:px-8 md:px-12 lg:px-20 xl:px-24 my-4">

        <header class="flex items-center justify-between whitespace-nowrap border border-ice/70 dark:border-gray-700 bg-startup-white dark:bg-background-dark rounded-t-xl px-6 py-3 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="size-8 flex items-center justify-center rounded-full bg-resilient-turquoise/10 text-resilient-turquoise">
                    <span class="material-symbols-outlined text-[28px]">inventory_2</span>
                </div>
                <div>
                    <h2 class="text-immersive-blue-black dark:text-startup-white text-lg font-bold leading-tight tracking-[-0.015em]">Sistema de Inventario</h2>
                    <p class="text-xs text-immersive-blue-black/60 dark:text-gray-400">Sesión de <?= $nombreUsuario ?></p>
                </div>
            </div>
            <span class="inline-flex items-center gap-2 rounded-full bg-empowered-yellow/20 px-3 py-1 text-xs font-semibold text-immersive-blue-black">
                <span class="w-2 h-2 rounded-full bg-empowered-yellow"></span> Inventario activo
            </span>
        </header>

        <main class="bg-startup-white dark:bg-background-dark flex-grow rounded-b-xl shadow-sm border-x border-b border-ice/70 dark:border-gray-700 min-h-[70vh] flex flex-col">
            <div class="flex flex-wrap justify-between gap-4 px-6 pt-4 pb-3 border-b border-ice/70 dark:border-gray-800">
                <div class="flex min-w-72 flex-col gap-1.5">
                    <p class="text-immersive-blue-black dark:text-startup-white text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em]">Inventario de productos</p>
                    <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base">Gestiona y visualiza todos los productos de tu inventario.</p>
                </div>
                <a href="../Controlador/controladorProducto.php?accion=agregar" class="hidden sm:flex min-w-[140px] max-w-[260px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 md:h-11 px-5 bg-resilient-turquoise text-startup-white text-sm font-bold gap-2 shadow-sm hover:bg-immersive-blue-black transition">
                    <span class="material-symbols-outlined">add_circle</span><span class="truncate">Nuevo producto</span>
                </a>
            </div>

            <div class="px-2 sm:px-4 md:px-6 py-3 flex-1">
                <div class="overflow-x-auto rounded-xl border border-ice dark:border-gray-700 bg-startup-white dark:bg-background-dark/80">
                    <table class="w-full min-w-[700px] flex-1">
                        <thead>
                            <tr class="bg-ice/60 dark:bg-gray-800">
                                <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold uppercase tracking-wide w-[20%]">Nombre producto</th>
                                <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold uppercase tracking-wide w-[10%]">ID producto</th>
                                <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold uppercase tracking-wide w-[30%]">Descripción</th>
                                <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold uppercase tracking-wide w-[10%]">Stock</th>
                                <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold uppercase tracking-wide w-[15%]">Fecha ingreso</th>
                                <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold uppercase tracking-wide w-[15%]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($datos['productos'])) : ?>
                            <?php foreach ($datos['productos'] as $prod) : 
                                $currentId = (string)$prod['_id']; // Convertir ObjectId a string una sola vez
                            ?>
                                <tr class="border-t border-t-ice dark:border-t-gray-700 hover:bg-ice/40 dark:hover:bg-gray-800 transition">
                                    <?php if ($editingId === $currentId) : ?>
                                        <form method="POST" action="../Controlador/controladorProducto.php?accion=editar" class="table-row-form-edit">
                                            <td class="px-4 py-3 text-sm">
                                                <input type="hidden" name="idProducto" value="<?= htmlspecialchars($currentId) ?>" />
                                                <input type="text" name="nombreP" value="<?= htmlspecialchars($prod['nombreP'] ?? '') ?>" class="form-input rounded-lg w-full text-sm border-ice" required />
                                            </td>
                                            <td class="px-4 py-3 text-sm font-mono"><?= htmlspecialchars(substr($currentId, -6)) ?>...</td>
                                            <td class="px-4 py-3 text-sm">
                                                <input type="text" name="descripcionP" value="<?= htmlspecialchars($prod['descripcionP'] ?? '') ?>" class="form-input rounded-lg w-full text-sm border-ice" />
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <input type="number" name="stock" value="<?= htmlspecialchars($prod['stock'] ?? 0) ?>" min="0" class="form-input rounded-lg w-full text-sm border-ice" required />
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="whitespace-nowrap"><?= is_object($prod['fechaI']) ? $prod['fechaI']->toDateTime()->format('d/m/Y') : htmlspecialchars($prod['fechaI'] ?? '') ?></span>
                                            </td>
                                            <td class="px-4 py-3 flex gap-2">
                                                <button type="submit" class="bg-sustainable-green text-white px-3 py-1 rounded-full shadow-sm"><span class="material-symbols-outlined text-base">save</span></button>
                                                <a href="../Controlador/controladorProducto.php?accion=listar" class="bg-ice text-immersive-blue-black px-3 py-1 rounded-full shadow-sm"><span class="material-symbols-outlined text-base">cancel</span></a>
                                            </td>
                                        </form>
                                    <?php else : ?>
                                        <td class="px-4 py-3 text-sm font-medium whitespace-nowrap"><?= htmlspecialchars($prod['nombreP'] ?? '') ?></td>
                                        <td class="px-4 py-3 text-sm font-mono whitespace-nowrap" title="<?= $currentId ?>">
                                            <?= htmlspecialchars(substr($currentId, -6)) ?>...
                                        </td>
                                        <td class="px-4 py-3 text-sm"><?= htmlspecialchars($prod['descripcionP'] ?? '') ?></td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap">
                                            <div class="inline-flex items-center justify-center rounded-full h-7 px-3 text-xs font-medium 
                                                <?= ($prod['stock'] ?? 0) > 10 ? 'bg-green-100 text-green-700' : (($prod['stock'] ?? 0) > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-700') ?>">
                                                <?= htmlspecialchars($prod['stock'] ?? 0) ?>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap">
                                            <?= is_object($prod['fechaI'] ?? null) ? $prod['fechaI']->toDateTime()->format('d/m/Y') : htmlspecialchars($prod['fechaI'] ?? '') ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-bold whitespace-nowrap">
                                            <div class="flex gap-1">
                                                <a href="../Controlador/controladorProducto.php?accion=listar&editar=<?= urlencode($currentId) ?>" class="p-2 rounded-full text-resilient-turquoise hover:bg-ice" title="Editar"><span class="material-symbols-outlined text-lg">edit</span></a>
                                                <a href="../Controlador/controladorProducto.php?accion=eliminar&id=<?= urlencode($currentId) ?>" class="p-2 rounded-full text-red-600 hover:bg-red-50" onclick="return confirm('¿Eliminar <?= htmlspecialchars($prod['nombreP'] ?? 'este producto') ?>?');" title="Eliminar"><span class="material-symbols-outlined text-lg">delete</span></a>
                                                <a href="../Controlador/controladorProducto.php?accion=devolucion&id=<?= urlencode($currentId) ?>" class="p-2 rounded-full text-resilient-turquoise hover:bg-ice" title="Registrar devolución"><span class="material-symbols-outlined text-lg">undo</span></a>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan='6' class='p-8 text-center text-gray-500'>No hay productos registrados. Presiona "Nuevo producto" para comenzar.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <footer class="mt-auto flex flex-col sm:flex-row items-center justify-center gap-3 px-6 pb-4 pt-3 bg-background-light dark:bg-gray-800/60 rounded-b-xl border-t border-ice/70 dark:border-gray-700">
                <a href="../Controlador/controladorProducto.php?accion=agregar" class="w-full sm:w-auto flex min-w-[120px] cursor-pointer items-center justify-center rounded-full h-10 px-4 bg-resilient-turquoise text-startup-white text-sm font-bold gap-2 shadow-md">
                    <span class="material-symbols-outlined text-base">add_circle</span><span class="truncate">Nuevo producto</span>
                </a>
                <a href="../Controlador/controladorProducto.php?accion=salida" class="w-full sm:w-auto flex min-w-[120px] cursor-pointer items-center justify-center rounded-full h-10 px-4 bg-white text-immersive-blue-black border border-ice text-sm font-bold gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-base">arrow_upward</span><span class="truncate">Nueva salida</span>
                </a>
                <a href="../Controlador/controladorProducto.php?accion=movimientos" class="w-full sm:w-auto flex min-w-[120px] cursor-pointer items-center justify-center rounded-full h-10 px-4 bg-white text-immersive-blue-black border border-ice text-sm font-bold gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-base">sync_alt</span><span class="truncate">Ver movimientos</span>
                </a>
                <div class="hidden sm:block flex-grow"></div>
                <a href="../Controlador/controladorCerrarSesion.php" class="w-full sm:w-auto flex min-w-[120px] cursor-pointer items-center justify-center rounded-full h-10 px-4 bg-immersive-blue-black text-startup-white text-sm font-bold gap-2 shadow-md">
                    <span class="material-symbols-outlined text-base">logout</span><span class="truncate">Cerrar sesión</span>
                </a>
            </footer>
        </main>
    </div>
</div>
</body>
</html>