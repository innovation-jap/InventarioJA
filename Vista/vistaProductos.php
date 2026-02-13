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
    <title>Junnior Achievement</title>
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

        /* Alineación fija para pestañas de navegación */
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
                        <span class="material-symbols-outlined !text-2xl font-bold">inventory_2</span>
                    </div>
                    <h2 class="text-xl font-black tracking-tight text-slate-900 dark:text-white uppercase">Junnior Achievement</h2>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="hidden md:flex items-center ml-10">
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                        <input id="inputBuscar" 
                            class="w-80 bg-slate-100 dark:bg-background-dark/50 border-none rounded-xl pl-12 pr-4 h-11 focus:ring-2 focus:ring-primary/50 text-sm transition-all text-slate-900 dark:text-white" 
                            placeholder="Buscar por nombre..." 
                            type="text"/>
                    </div>
                </div>
                
                <a href="../Controlador/controladorProducto.php?accion=agregar" class="bg-primary hover:bg-primary/80 text-slate-900 px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined">add</span>
                    <span class="hidden sm:inline">Nuevo producto</span>
                </a>

                <div class="flex items-center gap-3 pl-6 border-l border-slate-200 dark:border-slate-700">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sesión de</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight"><?= $nombreUsuario ?></p>
                    </div>
                    <img alt="Avatar" class="size-11 rounded-full border-2 border-primary" src="https://ui-avatars.com/api/?name=<?= urlencode($nombreUsuario) ?>&background=0df2f2&color=102222"/>
                </div>
            </div>
        </div>
    </header>

    <main class="animate-page max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 py-10 w-full flex-1">
        
        <div class="flex mb-10">
            <nav class="nav-tabs bg-white dark:bg-slate-800 p-1.5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
                <a href="?accion=listar" class="flex-1 px-6 py-2.5 rounded-xl text-sm font-bold bg-primary text-slate-900 shadow-md text-center">INVENTARIO</a>
                <a href="../Controlador/controladorProducto.php?accion=movimientos" class="flex-1 px-6 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:text-primary transition-all text-center">MOVIMIENTOS</a>
            </nav>
        </div>

        <div class="mb-10">
            <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Inventario General</h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg">Administración de stock, devoluciones y bajas de productos.</p>
        </div>

        <div id="contenedorProductos" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php if (!empty($datos['productos'])) : ?>
                <?php foreach ($datos['productos'] as $prod) : 
                    $idStr = (string)$prod['_id'];
                    $stock = $prod['stock'] ?? 0;
                ?>
                <div class="tarjeta-producto bg-white dark:bg-background-dark/80 rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden group hover:shadow-2xl transition-all duration-300 flex flex-col">
                    <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://placehold.co/600x400/102222/0df2f2?text=<?= urlencode($prod['nombreP']) ?>"/>
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary/90 text-slate-900 text-[10px] font-black px-3 py-1.5 rounded-lg shadow-xl uppercase tracking-widest">
                                <?= htmlspecialchars($prod['almacen'] ?? 'General') ?>
                            </span>
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 leading-tight"><?= htmlspecialchars($prod['nombreP']) ?></h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 line-clamp-3">
                            <?= htmlspecialchars($prod['descripcionP'] ?? 'Sin descripción adicional para este producto.') ?>
                        </p>
                        
                        <div class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-800">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Disponible</span>
                                    <span class="text-2xl font-black <?= $stock <= 5 ? 'text-rose-500 animate-pulse' : 'text-slate-900 dark:text-white' ?>"><?= $stock ?></span>
                                </div>
                                
                                <div class="flex items-center gap-1">
                                    <button onclick="abrirModalSalida('<?= $idStr ?>')" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-rose-500 hover:bg-rose-500 hover:text-white transition-all" title="Registrar Salida">
                                        <span class="material-symbols-outlined">trending_down</span>
                                    </button>
                                    <a href="../Controlador/controladorProducto.php?accion=devolucion&id=<?= urlencode($idStr) ?>" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all" title="Devolución">
                                        <span class="material-symbols-outlined">undo</span>
                                    </a>
                                    <a href="?accion=listar&editar=<?= urlencode($idStr) ?>" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-primary transition-all" title="Editar">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <a href="../Controlador/controladorProducto.php?accion=eliminar&id=<?= urlencode($idStr) ?>" 
                                       onclick="return confirm('¿Estás seguro de eliminar este producto del inventario?');" 
                                       class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 hover:bg-rose-600 hover:text-white transition-all" title="Eliminar Producto">
                                        <span class="material-symbols-outlined">delete</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-span-full py-20 text-center bg-white dark:bg-slate-800/50 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <span class="material-symbols-outlined !text-6xl text-slate-300 mb-4">inventory_2</span>
                    <p class="text-slate-500 text-lg">No se encontraron productos registrados.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-white dark:bg-[#152a2a] border-t border-slate-100 dark:border-slate-800 py-8 mt-10">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-8">
                <button onclick="abrirModalSalida()" class="text-slate-600 dark:text-slate-400 hover:text-rose-500 font-bold flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined">logout</span> REGISTRAR SALIDA
                </button>
                <a href="../Controlador/controladorProducto.php?accion=movimientos" class="text-slate-600 dark:text-slate-400 hover:text-primary font-bold flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined">history</span> HISTORIAL DE MOVIMIENTOS
                </a>
            </div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">© 2026 Sistema Inventory Pro - Donde Patty</p>
        </div>
    </footer>
</div>

<div id="modalSalida" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="cerrarModalSalida()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white dark:bg-background-dark p-8 shadow-2xl border border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-4 mb-8">
                <div class="bg-rose-500 size-12 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-500/30">
                    <span class="material-symbols-outlined !text-2xl font-bold">arrow_downward</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Baja de Stock</h3>
                    <p class="text-xs text-slate-500">Registrar salida de mercadería.</p>
                </div>
            </div>

            <form method="POST" action="../Controlador/controladorProducto.php?accion=salida" class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Producto</label>
                    <select name="idProducto" id="modalIdProducto" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-14 px-4 text-sm focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white">
                        <option value="">Selecciona...</option>
                        <?php if (!empty($datos['productos'])) : ?>
                            <?php foreach ($datos['productos'] as $prod) : ?>
                                <option value="<?= (string)$prod['_id'] ?>" data-stock="<?= $prod['stock'] ?>">
                                    <?= htmlspecialchars($prod['nombreP']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Cantidad</label>
                        <input type="number" name="cantidad" min="1" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-14 px-4 text-lg font-black text-rose-500 focus:ring-2 focus:ring-rose-500/50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">En Stock</label>
                        <input type="text" id="modalStockLabel" disabled class="w-full bg-slate-100 dark:bg-slate-900/50 border-none rounded-xl h-14 px-4 text-sm text-slate-400 font-bold cursor-not-allowed">
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="cerrarModalSalida()" class="flex-1 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-200 transition-all">CANCELAR</button>
                    <button type="submit" class="flex-1 h-14 rounded-2xl bg-rose-500 text-white font-black shadow-xl shadow-rose-500/20 hover:bg-rose-600 transition-all">CONFIRMAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Lógica de Modales
const modalS = document.getElementById('modalSalida');
const selProd = document.getElementById('modalIdProducto');
const labStock = document.getElementById('modalStockLabel');

function abrirModalSalida(id = null) {
    modalS.classList.remove('hidden');
    if (id) {
        selProd.value = id;
        actualizarLabel();
    }
}

function cerrarModalSalida() { modalS.classList.add('hidden'); }

function actualizarLabel() {
    const opt = selProd.options[selProd.selectedIndex];
    labStock.value = opt.getAttribute('data-stock') || '0';
}

selProd.addEventListener('change', actualizarLabel);
document.addEventListener('keydown', (e) => { if (e.key === 'Escape') cerrarModalSalida(); });

// LÓGICA DE BÚSQUEDA CORREGIDA
const inputBuscar = document.getElementById('inputBuscar');

inputBuscar.addEventListener('input', function () {
    const termino = this.value.toLowerCase().trim();
    // Selector corregido para las tarjetas
    const tarjetas = document.querySelectorAll('.tarjeta-producto'); 

    tarjetas.forEach(tarjeta => {
        const nombre = tarjeta.querySelector('h3').textContent.toLowerCase();
        
        if (nombre.includes(termino)) {
            tarjeta.style.display = 'flex'; 
            tarjeta.classList.remove('animate-page');
            // Forzar reinicio de animación
            void tarjeta.offsetWidth; 
            tarjeta.classList.add('animate-page');
        } else {
            tarjeta.style.display = 'none';
        }
    });
});
</script>

</body>
</html>