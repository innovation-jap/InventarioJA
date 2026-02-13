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
?>
<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Donde Patty - Sistema de Inventario</title>
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
</head>
<body class="bg-background-light dark:bg-background-dark min-h-screen text-slate-800 dark:text-slate-100 font-display">

<div class="flex flex-col min-h-screen">
    <header class="sticky top-0 z-50 w-full bg-white dark:bg-[#152a2a] border-b border-primary/10 shadow-sm">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 h-20 flex items-center justify-between">
            <div class="flex items-center gap-10">
                <div class="flex items-center gap-3">
                    <div class="bg-primary size-10 rounded-xl flex items-center justify-center text-slate-900">
                        <span class="material-symbols-outlined !text-2xl font-bold">inventory_2</span>
                    </div>
                    <h2 class="text-xl font-black tracking-tight text-slate-900 dark:text-white uppercase">Donde Patty</h2>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <a href="../Controlador/controladorProducto.php?accion=agregar" class="bg-primary hover:bg-primary/80 text-slate-900 px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined">add</span>
                    <span class="hidden sm:inline">Nuevo producto</span>
                </a>
                <div class="flex items-center gap-3 pl-6 border-l border-slate-200 dark:border-slate-700">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sesión de</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight"><?= $nombreUsuario ?></p>
                    </div>
                    <div class="size-11 rounded-full bg-primary/20 p-0.5 border-2 border-primary overflow-hidden">
                        <img alt="Avatar" src="https://ui-avatars.com/api/?name=<?= urlencode($nombreUsuario) ?>&background=0df2f2&color=102222"/>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 py-10 w-full flex-1">
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Inventario</h1>
                <p class="text-slate-500 dark:text-slate-400 text-lg">Visualización por tarjetas con gestión rápida.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php if (!empty($datos['productos'])) : ?>
                <?php foreach ($datos['productos'] as $prod) : 
                    $idStr = (string)$prod['_id'];
                    $stock = $prod['stock'] ?? 0;
                ?>
                <div class="bg-white dark:bg-background-dark/80 rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden group hover:shadow-2xl hover:shadow-primary/5 transition-all duration-300">
                    <div class="relative h-52 w-full overflow-hidden bg-slate-100">
                        <img class="w-full h-full object-cover" src="https://placehold.co/600x400/102222/0df2f2?text=<?= urlencode($prod['nombreP']) ?>"/>
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary/90 text-slate-900 text-[10px] font-black px-3 py-1.5 rounded-lg shadow-xl uppercase tracking-widest">
                                <?= htmlspecialchars($prod['almacen'] ?? 'General') ?>
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2"><?= htmlspecialchars($prod['nombreP']) ?></h3>
                        <p class="text-slate-500 text-xs line-clamp-2 mb-6"><?= htmlspecialchars($prod['descripcionP'] ?? 'Sin descripción.') ?></p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-50 dark:border-slate-800">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase">Stock</span>
                                <span class="text-lg font-black <?= $stock <= 5 ? 'text-rose-500' : 'text-slate-900 dark:text-white' ?>"><?= $stock ?></span>
                            </div>
                            
                            <div class="flex items-center gap-1">
                                <button onclick="abrirModalSalida('<?= $idStr ?>')" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-500 hover:bg-rose-500/10 hover:text-rose-500 transition-all" title="Salida">
                                    <span class="material-symbols-outlined">trending_down</span>
                                </button>
                                <a href="?accion=listar&editar=<?= $idStr ?>" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-500 hover:bg-primary/20 hover:text-primary transition-all">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-white dark:bg-[#152a2a] border-t border-slate-100 dark:border-slate-800 py-6 mt-10">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 flex justify-between items-center">
            <div class="flex gap-6 text-sm">
                <button onclick="abrirModalSalida()" class="text-slate-600 dark:text-slate-400 hover:text-primary font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">logout</span> Registrar Salida
                </button>
            </div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">© 2026 Donde Patty</p>
        </div>
    </footer>
</div>

<div id="modalSalida" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="cerrarModalSalida()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center">
        <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-background-dark p-8 text-left shadow-2xl transition-all border border-slate-100 dark:border-slate-800">
            
            <div class="flex items-center gap-4 mb-6">
                <div class="bg-rose-500/10 text-rose-500 size-12 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined !text-3xl">arrow_downward</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Nueva Salida</h3>
                    <p class="text-sm text-slate-500">Restar stock del inventario.</p>
                </div>
            </div>

            <form method="POST" action="../Controlador/controladorProducto.php?accion=salida" class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Producto Seleccionado</label>
                    <select name="idProducto" id="modalIdProducto" required 
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-12 px-4 text-sm focus:ring-2 focus:ring-primary/50 transition-all text-slate-900 dark:text-white">
                        <option value="">Selecciona un producto...</option>
                        <?php if (!empty($datos['productos'])) : ?>
                            <?php foreach ($datos['productos'] as $prod) : ?>
                                <option value="<?= (string)$prod['_id'] ?>" data-stock="<?= $prod['stock'] ?>">
                                    <?= htmlspecialchars($prod['nombreP']) ?> (Actual: <?= $prod['stock'] ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Cant. Salida</label>
                        <input type="number" name="cantidad" min="1" required placeholder="0"
                               class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-12 px-4 text-sm font-bold focus:ring-2 focus:ring-primary/50 transition-all text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Stock en DB</label>
                        <input type="text" id="modalStockLabel" disabled
                               class="w-full bg-slate-100 dark:bg-slate-900/50 border-none rounded-xl h-12 px-4 text-sm text-slate-400 cursor-not-allowed">
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="cerrarModalSalida()" 
                            class="flex-1 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-200 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 h-12 rounded-xl bg-rose-500 text-white font-bold text-sm shadow-lg shadow-rose-500/20 hover:bg-rose-600 transition-all">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const modalS = document.getElementById('modalSalida');
const selProd = document.getElementById('modalIdProducto');
const labStock = document.getElementById('modalStockLabel');

/**
 * Abre el modal. Si recibe un ID, preselecciona el producto.
 */
function abrirModalSalida(id = null) {
    modalS.classList.remove('hidden');
    if (id) {
        selProd.value = id;
        actualizarLabel();
    }
}

function cerrarModalSalida() {
    modalS.classList.add('hidden');
}

/**
 * Muestra el stock del producto elegido en el input deshabilitado.
 */
function actualizarLabel() {
    const opt = selProd.options[selProd.selectedIndex];
    const stock = opt.getAttribute('data-stock') || '0';
    labStock.value = stock;
}

selProd.addEventListener('change', actualizarLabel);

// Cerrar con la tecla ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') cerrarModalSalida();
});
</script>

</body>
</html>