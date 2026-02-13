<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

$nombreUsuario = htmlspecialchars($_SESSION['nombreU'] ?? 'Usuario');
$paginaActual = $datos['paginaActual'] ?? 1;
$totalPaginas = $datos['totalPaginas'] ?? 1;
// Nota: La lógica de $editingId la manejaremos vía Modal o página aparte para no romper el diseño de cards
?>
<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Sistema de Inventario - Donde Patty</title>
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
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-size: 20px; vertical-align: middle; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark min-h-screen text-slate-800 dark:text-slate-100">
<div class="flex flex-col min-h-screen">
    
    <header class="sticky top-0 z-50 w-full bg-white dark:bg-[#152a2a] border-b border-primary/10 shadow-sm">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 h-20 flex items-center justify-between">
            <div class="flex items-center gap-10">
                <div class="flex items-center gap-3">
                    <div class="bg-primary size-10 rounded-xl flex items-center justify-center text-slate-900">
                        <span class="material-symbols-outlined !text-2xl font-bold">inventory_2</span>
                    </div>
                    <h2 class="text-xl font-black tracking-tight text-slate-900 dark:text-white uppercase">Inventario JA</h2>
                </div>
                <div class="hidden md:flex items-center">
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                        <input class="w-80 bg-slate-100 dark:bg-background-dark/50 border-none rounded-xl pl-12 pr-4 h-11 focus:ring-2 focus:ring-primary/50 text-sm transition-all" id="inputBuscar" placeholder="Buscar productos..." type="text"/>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <a href="../Controlador/controladorProducto.php?accion=agregar" class="bg-primary hover:bg-primary/80 text-slate-900 px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined">add</span>
                    <span>Nuevo producto</span>
                </a>
                <div class="flex items-center gap-3 pl-6 border-l border-slate-200 dark:border-slate-700">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sesión de</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight"><?= $nombreUsuario ?></p>
                    </div>
                    <div class="size-11 rounded-full bg-primary/20 p-0.5 border-2 border-primary overflow-hidden">
                        <img alt="Avatar" class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name=<?= urlencode($nombreUsuario) ?>&background=0df2f2&color=102222"/>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 py-10 w-full flex-1">
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Inventario de Productos</h1>
                <p class="text-slate-500 dark:text-slate-400 max-w-xl text-lg">Gestiona y visualiza tus productos en tiempo real.</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 hover:text-primary transition-colors shadow-sm">
                    <span class="material-symbols-outlined">grid_view</span>
                </button>
                <div class="w-px h-6 bg-slate-300 dark:bg-slate-600 mx-1"></div>
                <a href="../Controlador/controladorCerrarSesion.php" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-rose-50 border border-rose-200 text-sm font-semibold text-rose-700 hover:bg-rose-100 transition-all shadow-sm">
                    <span class="material-symbols-outlined">logout</span>
                    <span>Cerrar</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            
            <?php if (!empty($datos['productos'])) : ?>
                <?php foreach ($datos['productos'] as $prod) : 
                    $idStr = (string)$prod['_id'];
                    $stock = $prod['stock'] ?? 0;
                ?>
                <div class="bg-white dark:bg-background-dark/80 rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden group hover:shadow-2xl hover:shadow-primary/5 transition-all duration-300 hover:-translate-y-1">
                    <div class="relative h-56 w-full overflow-hidden bg-slate-100">
                        <img alt="<?= htmlspecialchars($prod['nombreP']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                             src="<?= !empty($prod['imagen']) ? $prod['imagen'] : 'https://placehold.co/600x400/102222/0df2f2?text=' . urlencode($prod['nombreP']) ?>"/>
                        
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary/90 text-slate-900 text-[10px] font-black px-3 py-1.5 rounded-lg shadow-xl uppercase tracking-widest">
                                <?= htmlspecialchars($prod['almacen'] ?? 'General') ?>
                            </span>
                        </div>
                        <div class="absolute top-4 right-4 bg-white/90 dark:bg-slate-900/90 p-2 rounded-xl backdrop-blur-sm shadow-xl">
                            <span class="text-sm font-bold <?= $stock <= 5 ? 'text-rose-600' : 'text-slate-900 dark:text-white' ?> flex items-center gap-1">
                                <span class="material-symbols-outlined text-primary font-bold">inventory_2</span> <?= $stock ?>
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white leading-tight mb-2"><?= htmlspecialchars($prod['nombreP']) ?></h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6 line-clamp-2">
                            <?= htmlspecialchars($prod['descripcionP'] ?? 'Sin descripción disponible.') ?>
                        </p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-50 dark:border-slate-800">
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase font-black text-slate-400 tracking-wider">Ingreso</span>
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">
                                    <?php 
                                        if (isset($prod['fechaI']) && is_object($prod['fechaI'])) {
                                            $fecha = $prod['fechaI']->toDateTime();
                                            $fecha->setTimezone(new DateTimeZone('America/Lima'));
                                            echo $fecha->format('d M, Y');
                                        } else { echo 'N/A'; }
                                    ?>
                                </span>
                            </div>
                            <div class="flex items-center gap-1">
                                <a href="../Controlador/controladorProducto.php?accion=listar&editar=<?= urlencode($idStr) ?>" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-500 hover:bg-primary/20 hover:text-primary transition-all" title="Editar">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <a href="../Controlador/controladorProducto.php?accion=devolucion&id=<?= urlencode($idStr) ?>" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-500 hover:bg-emerald-500/10 hover:text-emerald-500 transition-all" title="Devolución">
                                    <span class="material-symbols-outlined">undo</span>
                                </a>
                                <a href="../Controlador/controladorProducto.php?accion=eliminar&id=<?= urlencode($idStr) ?>" onclick="return confirm('¿Eliminar producto?');" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-500 hover:bg-rose-500/20 hover:text-rose-500 transition-all" title="Eliminar">
                                    <span class="material-symbols-outlined">delete</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-span-full py-20 text-center bg-white dark:bg-slate-800 rounded-2xl border-2 border-dashed border-slate-200">
                    <p class="text-slate-500">No hay productos registrados.</p>
                </div>
            <?php endif; ?>

            <a href="../Controlador/controladorProducto.php?accion=agregar" class="bg-primary/5 rounded-2xl border-2 border-dashed border-primary/30 flex flex-col items-center justify-center p-8 text-center group cursor-pointer hover:bg-primary/10 transition-all duration-300">
                <div class="bg-primary size-16 rounded-full flex items-center justify-center text-slate-900 mb-4 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined !text-4xl">add</span>
                </div>
                <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Añadir Nuevo</h4>
                <p class="text-sm text-slate-500 dark:text-slate-400">Expande tu catálogo</p>
            </a>
        </div>

        <?php if ($totalPaginas > 1) : ?>
        <div class="mt-12 flex justify-center">
            <nav class="flex gap-2">
                <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                    <a href="?accion=listar&p=<?= $i ?>" class="size-10 flex items-center justify-center rounded-xl font-bold transition-all <?= $i == $paginaActual ? 'bg-primary text-slate-900 shadow-lg shadow-primary/30' : 'bg-white dark:bg-slate-800 text-slate-500 hover:border-primary border border-transparent' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </nav>
        </div>
        <?php endif; ?>
    </main>

    <footer class="bg-white dark:bg-[#152a2a] border-t border-slate-100 dark:border-slate-800 py-6 mt-10">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-8 text-sm font-medium text-slate-600 dark:text-slate-400">
                <div class="flex items-center gap-2">
                    <div class="size-2 rounded-full bg-primary"></div>
                    <span>Total: <span class="text-slate-900 dark:text-white font-bold"><?= count($datos['productos'] ?? []) ?></span></span>
                </div>
                <a href="../Controlador/controladorProducto.php?accion=movimientos" class="hover:text-primary transition-colors">Ver Movimientos</a>
                <a href="../Controlador/controladorProducto.php?accion=salida" class="hover:text-primary transition-colors">Registrar Salida</a>
            </div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">© 2026 Sistema Donde Patty</p>
        </div>
    </footer>
</div>
</body>
</html>