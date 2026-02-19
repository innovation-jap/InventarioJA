<?php
require_once('../Controlador/controladorProducto.php');

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

$nombreUsuario = htmlspecialchars($_SESSION['nombreU'] ?? 'Usuario');

// Filtros para MongoDB
$filtered_get = $_GET;
unset($filtered_get['accion']); 
$filter_query = http_build_query(array_filter($filtered_get));

$current_id_producto = $_GET['id_producto'] ?? '';
$current_id_usuario = $_GET['id_usuario'] ?? '';
$current_fecha_inicio = $_GET['fecha_inicio'] ?? '';
$current_fecha_fin = $_GET['fecha_fin'] ?? '';
?>
<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Movimientos - Junnior Achievement</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0df2f2",
                        "background-light": "#f5f8f8",
                        "background-dark": "#102222",
                    },
                    fontFamily: { "display": ["Inter", "sans-serif"] },
                },
            },
        }
    </script>
    <style>
        /* Animación de entrada suave */
        @keyframes fadeInSlide {
            0% { opacity: 0; transform: translateY(15px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .animate-page {
            animation: fadeInSlide 0.5s ease-out forwards;
        }

        /* Evitar saltos visuales en las pestañas */
        .nav-tabs {
            min-width: 320px;
            display: inline-flex;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark min-h-screen text-slate-800 dark:text-slate-100 font-display">

<div class="flex flex-col min-h-screen">
    <header class="sticky top-0 z-50 w-full bg-white dark:bg-[#152a2a] border-b border-primary/10 shadow-sm">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 h-20 flex items-center justify-between">
            <div class="flex items-center gap-10">
                <div class="flex items-center gap-3">
                    <div class="bg-primary size-10 rounded-xl flex items-center justify-center text-slate-900 shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined !text-2xl font-bold">sync_alt</span>
                    </div>
                    <h2 class="text-xl font-black tracking-tight text-slate-900 dark:text-white uppercase">JUNNIOR ACHIEVEMENT</h2>
                </div>
            </div>
            <div class="flex items-center gap-3 pl-6">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-semibold text-slate-500 uppercase">Sesión de</p>
                    <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight"><?= $nombreUsuario ?></p>
                </div>
                <img alt="Avatar" class="size-11 rounded-full border-2 border-primary" src="https://ui-avatars.com/api/?name=<?= urlencode($nombreUsuario) ?>&background=0df2f2&color=102222"/>
            </div>
        </div>
    </header>

    <main class="animate-page max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 py-10 w-full flex-1">
        
        <div class="flex mb-10">
            <div class="nav-tabs bg-white dark:bg-slate-800 p-1.5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
                <a href="../Controlador/controladorProducto.php?accion=listar" class="flex-1 px-6 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-center">INVENTARIO</a>
                <a href="#" class="flex-1 px-6 py-2.5 rounded-xl text-sm font-bold bg-primary text-slate-900 shadow-md text-center">MOVIMIENTOS</a>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Registro Histórico</h1>
                <p class="text-slate-500 dark:text-slate-400 text-lg">Auditoría completa de entradas y salidas.</p>
            </div>
            <a href="../Controlador/controladorProducto.php?accion=exportar&<?= htmlspecialchars($filter_query) ?>" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg shadow-emerald-500/20 transition-all">
                <span class="material-symbols-outlined">download</span> Exportar CSV
            </a>
        </div>

        <div class="bg-white dark:bg-slate-800/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-700 mb-8 shadow-sm">
            <form method="GET" action="../Controlador/controladorProducto.php" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <input type="hidden" name="accion" value="movimientos" />
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Producto</label>
                    <select name="id_producto" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-12 px-4 text-sm focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white">
                        <option value="">-- Todos --</option>
                        <?php foreach ($datos['productos'] as $producto): 
                            $idP = (string)$producto['_id']; ?>
                            <option value="<?= $idP ?>" <?= $current_id_producto == $idP ? 'selected' : '' ?>><?= htmlspecialchars($producto['nombreP']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Usuario</label>
                    <select name="id_usuario" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-12 px-4 text-sm focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white">
                        <option value="">-- Todos --</option>
                        <?php foreach ($datos['usuarios'] as $u): 
                            $idU = (string)$u['_id']; ?>
                            <option value="<?= $idU ?>" <?= $current_id_usuario == $idU ? 'selected' : '' ?>><?= htmlspecialchars($u['nombreU']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Desde</label>
                    <input type="date" name="fecha_inicio" value="<?= $current_fecha_inicio ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-12 px-4 text-sm focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Hasta</label>
                    <input type="date" name="fecha_fin" value="<?= $current_fecha_fin ?>" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-12 px-4 text-sm focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-slate-900 dark:bg-primary text-white dark:text-slate-900 h-12 rounded-xl font-bold text-sm hover:scale-[1.02] transition-all shadow-lg shadow-primary/10">
                        FILTRAR REGISTROS
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-background-dark/80 rounded-3xl border border-slate-100 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-700">
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Producto</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Tipo</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Cantidad</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Usuario</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 text-right">Fecha y Hora</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        <?php if (!empty($datos['movimientos'])) : ?>
                            <?php foreach ($datos['movimientos'] as $fila) : 
                                $tipo = strtolower($fila['tipo'] ?? 'salida');
                                $colorTipo = $tipo === 'entrada' ? 'text-emerald-500 bg-emerald-500/10' : ($tipo === 'devolucion' ? 'text-blue-500 bg-blue-500/10' : 'text-rose-500 bg-rose-500/10');
                                $icon = $tipo === 'entrada' ? 'arrow_upward' : ($tipo === 'devolucion' ? 'undo' : 'arrow_downward');
                            ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($fila['nombreP']) ?></p>
                                    <p class="text-[10px] text-slate-400 uppercase font-medium tracking-tighter"><?= htmlspecialchars($fila['almacen']) ?></p>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black uppercase <?= $colorTipo ?>">
                                        <span class="material-symbols-outlined !text-sm"><?= $icon ?></span>
                                        <?= $tipo ?>
                                    </span>
                                </td>
                                <td class="px-6 py-5 font-black text-lg <?= $tipo === 'salida' ? 'text-rose-500' : 'text-emerald-500' ?>">
                                    <?= $tipo === 'salida' ? '-' : '+' ?><?= $fila['cantidad'] ?>
                                </td>
                                <td class="px-6 py-5 text-sm font-medium text-slate-500"><?= htmlspecialchars($fila['nombreU']) ?></td>
                                <td class="px-6 py-5 text-right text-xs font-mono text-slate-400">
                                    <?php 
                                        if (isset($fila['fechaM']) && is_object($fila['fechaM'])) {
                                            $f = $fila['fechaM']->toDateTime();
                                            $f->setTimezone(new DateTimeZone('America/Lima'));
                                            echo $f->format('d/m/Y H:i');
                                        } else { echo $fila['fechaM']; }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="5" class="px-6 py-20 text-center text-slate-500">No hay movimientos que coincidan con los filtros.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>