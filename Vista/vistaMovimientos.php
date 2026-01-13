<?php
// Incluye el controlador para acceder a los datos.
require_once('../Controlador/controladorProducto.php');

// --- CORRECCIÓN DE FILTROS PARA MONGODB ---
$filtered_get = $_GET;
unset($filtered_get['accion']); 

// Construimos la query para exportación asegurando que los IDs sean strings
$filter_query = http_build_query(array_filter($filtered_get));

// Variables para mantener el estado de los filtros en el formulario
$current_id_producto = $_GET['id_producto'] ?? '';
$current_id_usuario = $_GET['id_usuario'] ?? '';
$current_fecha_inicio = $_GET['fecha_inicio'] ?? '';
$current_fecha_fin = $_GET['fecha_fin'] ?? '';
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Registro de Movimientos - Sistema de Inventario</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "immersive-blue-black": "#22404D",
                        "resilient-turquoise": "#00A0AF",
                        "empowered-yellow": "#E3E24F",
                        "sustainable-green": "#008B61",
                        "startup-white": "#FFFFFF",
                        "background-light": "#f6f6f8",
                        "background-dark": "#111621",
                        "ice": "#C3E5F5"
                    },
                    fontFamily: { "display": ["Inter", "sans-serif"] },
                    borderRadius: {"DEFAULT": "0.5rem", "lg": "0.75rem", "xl": "1rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark min-h-screen flex items-start justify-center px-4">
<div class="w-full max-w-[1200px] my-4">
    <header class="flex items-center justify-between whitespace-nowrap border border-ice/70 dark:border-gray-700 bg-startup-white dark:bg-background-dark rounded-t-xl px-6 py-3 shadow-sm">
        <div class="flex items-center gap-3 text-immersive-blue-black dark:text-startup-white">
            <div class="size-8 flex items-center justify-center rounded-full bg-resilient-turquoise/10 text-resilient-turquoise">
                <span class="material-symbols-outlined text-[26px]">sync_alt</span>
            </div>
            <div>
                <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Movimientos de inventario</h2>
                <p class="text-xs text-immersive-blue-black/60 dark:text-gray-400">Historial de entradas, salidas y devoluciones.</p>
            </div>
        </div>
    </header>

    <main class="bg-startup-white dark:bg-background-dark rounded-b-xl shadow-sm border-x border-b border-ice/70 dark:border-gray-700 min-h-[70vh] flex flex-col">
        <div class="flex flex-col gap-5 p-6 border-b border-ice/70 dark:border-gray-800">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex flex-col gap-1.5">
                    <p class="text-immersive-blue-black dark:text-startup-white text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em]">Registro de movimientos</p>
                    <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base">Filtra por fecha, producto o usuario.</p>
                </div>
            </div>

            <form method="GET" action="../Controlador/controladorProducto.php" class="flex flex-col gap-4">
                <input type="hidden" name="accion" value="movimientos" />

                <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="flex flex-col gap-1.5 col-span-2 sm:col-span-1">
                        <label for="id_producto" class="text-immersive-blue-black dark:text-startup-white text-xs font-semibold uppercase">Producto</label>
                        <select name="id_producto" id="id_producto" class="form-select rounded-lg text-sm border-gray-300 dark:bg-gray-700 dark:text-white">
                            <option value="">-- Todos --</option>
                            <?php foreach ($datos['productos'] as $producto): 
                                $idProd = (string)$producto['_id']; // MongoDB ID
                            ?>
                                <option value="<?= $idProd ?>" <?= $current_id_producto == $idProd ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($producto['nombreP'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1.5 col-span-2 sm:col-span-1">
                        <label for="id_usuario" class="text-immersive-blue-black dark:text-startup-white text-xs font-semibold uppercase">Usuario</label>
                        <select name="id_usuario" id="id_usuario" class="form-select rounded-lg text-sm border-gray-300 dark:bg-gray-700 dark:text-white">
                            <option value="">-- Todos --</option>
                            <?php foreach ($datos['usuarios'] as $usuario): 
                                $idUser = (string)$usuario['_id'];
                            ?>
                                <option value="<?= $idUser ?>" <?= $current_id_usuario == $idUser ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($usuario['nombreU'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1.5 col-span-1">
                        <label class="text-immersive-blue-black dark:text-startup-white text-xs font-semibold uppercase">Desde</label>
                        <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($current_fecha_inicio) ?>" class="form-input rounded-lg text-sm border-gray-300 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="flex flex-col gap-1.5 col-span-1">
                        <label class="text-immersive-blue-black dark:text-startup-white text-xs font-semibold uppercase">Hasta</label>
                        <input type="date" name="fecha_fin" value="<?= htmlspecialchars($current_fecha_fin) ?>" class="form-input rounded-lg text-sm border-gray-300 dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div class="col-span-2 lg:col-span-1 self-end">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-lg h-10 px-5 bg-resilient-turquoise text-startup-white text-sm font-bold hover:bg-immersive-blue-black transition shadow-md">
                            <span class="material-symbols-outlined text-base">search</span>Filtrar
                        </button>
                    </div>
                </div>
            </form>

            <div class="flex flex-col sm:flex-row gap-3 flex-wrap pt-4 border-t border-ice/70 dark:border-gray-800">
                <a href="../Controlador/controladorProducto.php?accion=listar" class="flex items-center justify-center gap-2 rounded-full h-10 px-4 bg-white text-immersive-blue-black text-sm font-bold border border-ice transition shadow-sm hover:bg-ice/20">
                    <span class="material-symbols-outlined text-base">arrow_back</span>Volver a inventario
                </a>
                <a href="../Controlador/controladorProducto.php?accion=exportar&<?= htmlspecialchars($filter_query) ?>" class="flex items-center justify-center gap-2 rounded-full h-10 px-4 bg-sustainable-green text-startup-white text-sm font-bold hover:bg-immersive-blue-black transition shadow-sm">
                    <span class="material-symbols-outlined text-base">download</span>Exportar CSV
                </a>
            </div>
        </div>

        <div class="px-6 py-3 flex-1 overflow-x-auto">
            <div class="flex overflow-hidden rounded-xl border border-ice dark:border-gray-700">
                <table class="min-w-[800px] table-auto w-full"> 
                    <thead class="bg-ice/60 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold uppercase tracking-wide">ID movimiento</th>
                        <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold uppercase tracking-wide">Producto</th>
                        <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold uppercase tracking-wide">Tipo</th>
                        <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold uppercase tracking-wide">Cantidad</th>
                        <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold uppercase tracking-wide">Usuario</th>
                        <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold uppercase tracking-wide">Fecha y hora</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($datos['movimientos'])) : ?>
                        <?php foreach ($datos['movimientos'] as $fila) : 
                            $tipo = strtolower($fila['tipo'] ?? 'salida');
                            $idMov = (string)$fila['idMovimiento'];
                        ?>
                            <tr class="border-t border-t-ice dark:border-t-gray-700 hover:bg-ice/40 dark:hover:bg-gray-800 transition">
                                <td class="px-4 py-3 text-sm font-mono whitespace-nowrap" title="<?= $idMov ?>">
                                    <?= substr($idMov, -6) ?>...
                                </td>
                                <td class="px-4 py-3 text-sm font-medium whitespace-nowrap"><?= htmlspecialchars($fila['nombreP'] ?? 'N/A') ?></td>
                                <td class="px-4 py-3 text-sm whitespace-nowrap">
                                    <?php 
                                        $badgeStyle = 'bg-red-100 text-red-700';
                                        $icon = 'arrow_downward';
                                        if ($tipo === 'entrada') { $badgeStyle = 'bg-green-100 text-green-700'; $icon = 'arrow_upward'; }
                                        elseif ($tipo === 'devolucion') { $badgeStyle = 'bg-blue-100 text-blue-700'; $icon = 'undo'; }
                                    ?>
                                    <div class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold <?= $badgeStyle ?>">
                                        <span class="material-symbols-outlined text-base"><?= $icon ?></span>
                                        <span><?= ucfirst($tipo) ?></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm font-bold whitespace-nowrap <?= $tipo === 'salida' ? 'text-red-700' : 'text-green-700' ?>">
                                    <?= $tipo === 'salida' ? '-' : '+' ?><?= htmlspecialchars($fila['cantidad'] ?? 0) ?>
                                </td>
                                <td class="px-4 py-3 text-sm"><?= htmlspecialchars($fila['nombreU'] ?? 'N/A') ?></td>
                                <td class="px-4 py-3 text-sm">
                                    <?php 
                                        if (isset($fila['fechaM']) && is_object($fila['fechaM'])) {
                                            echo $fila['fechaM']->toDateTime()->format('d/m/Y H:i:s');
                                        } else {
                                            echo htmlspecialchars($fila['fechaM'] ?? 'N/A');
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan='6' class='p-8 text-center text-gray-500'>No se encontraron movimientos.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>