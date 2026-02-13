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
    <title>Donde Patty - Inventario Completo</title>
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
                }
            }
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
                <div class="hidden md:flex items-center">
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                        <input id="inputBuscar" class="w-80 bg-slate-100 dark:bg-background-dark/50 border-none rounded-xl pl-12 pr-4 h-11 text-sm focus:ring-2 focus:ring-primary/50" placeholder="Buscar productos..." type="text"/>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <a href="../Controlador/controladorProducto.php?accion=agregar" class="bg-primary hover:bg-primary/80 text-slate-900 px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg shadow-primary/20 transition-all">
                    <span class="material-symbols-outlined">add</span> <span class="hidden sm:inline">Nuevo</span>
                </a>
                <div class="size-11 rounded-full bg-primary/20 p-0.5 border-2 border-primary overflow-hidden">
                    <img alt="Avatar" src="https://ui-avatars.com/api/?name=<?= urlencode($nombreUsuario) ?>&background=0df2f2&color=102222"/>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 py-8 w-full flex-1">
        <div class="flex items-center gap-1 mb-8 bg-white dark:bg-slate-800 p-1.5 rounded-2xl w-fit border border-slate-100 dark:border-slate-700 shadow-sm">
            <a href="?accion=listar" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-primary text-slate-900 shadow-md">Inventario</a>
            <a href="../Controlador/controladorProducto.php?accion=movimientos" class="px-6 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Movimientos</a>
            <a href="../Controlador/controladorProducto.php?accion=salida" class="px-6 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Salidas</a>
        </div>

        <div class="mb-10">
            <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Panel de Gestión</h1>
            <p class="text-slate-500 mt-1">Administra el stock y las operaciones de tus productos.</p>
        </div>

        <div id="contenedorCards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php if (!empty($datos['productos'])) : ?>
                <?php foreach ($datos['productos'] as $prod) : 
                    $idStr = (string)$prod['_id'];
                    $stock = $prod['stock'] ?? 0;
                ?>
                <div class="card-producto bg-white dark:bg-background-dark/80 rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden group hover:shadow-2xl transition-all duration-300">
                    <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                        <img class="w-full h-full object-cover" src="https://placehold.co/600x400/102222/0df2f2?text=<?= urlencode($prod['nombreP']) ?>"/>
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary/90 text-slate-900 text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-lg">
                                <?= htmlspecialchars($prod['almacen'] ?? 'Sótano') ?>
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="nombre-p text-xl font-bold text-slate-900 dark:text-white mb-2"><?= htmlspecialchars($prod['nombreP']) ?></h3>
                        
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase">Stock Actual</span>
                                <span class="text-xl font-black <?= $stock <= 5 ? 'text-rose-500' : 'text-slate-900 dark:text-white' ?>"><?= $stock ?></span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-50 dark:border-slate-800">
                            <div class="flex gap-1">
                                <button onclick="abrirModalSalida('<?= $idStr ?>')" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-rose-500 hover:bg-rose-500 hover:text-white transition-all" title="Salida">
                                    <span class="material-symbols-outlined">trending_down</span>
                                </button>
                                <a href="../Controlador/controladorProducto.php?accion=devolucion&id=<?= $idStr ?>" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all" title="Devolución">
                                    <span class="material-symbols-outlined">undo</span>
                                </a>
                            </div>

                            <div class="flex gap-1">
                                <a href="../Controlador/controladorProducto.php?accion=listar&editar=<?= $idStr ?>" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-primary transition-all" title="Editar">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <a href="../Controlador/controladorProducto.php?accion=eliminar&id=<?= $idStr ?>" onclick="return confirm('¿Seguro que deseas eliminar este producto?');" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-rose-500 transition-all" title="Eliminar">
                                    <span class="material-symbols-outlined">delete</span>
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
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-8 text-sm font-bold text-slate-500">
                <div class="flex items-center gap-2">
                    <div class="size-2 rounded-full bg-primary"></div>
                    <span>Total: <?= count($datos['productos'] ?? []) ?></span>
                </div>
                <a href="../Controlador/controladorProducto.php?accion=movimientos" class="flex items-center gap-1 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-lg">history</span> Ver Historial Completo
                </a>
            </div>
            <a href="../Controlador/controladorCerrarSesion.php" class="text-rose-500 text-xs font-black uppercase tracking-widest hover:underline">Cerrar Sesión</a>
        </div>
    </footer>
</div>

<div id="modalSalida" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="cerrarModalSalida()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white dark:bg-background-dark p-8 rounded-2xl shadow-2xl border border-slate-800">
            <h3 class="text-xl font-black mb-6 uppercase tracking-tight">Registrar Salida</h3>
            <form method="POST" action="../Controlador/controladorProducto.php?accion=salida" class="space-y-4">
                <select name="idProducto" id="modalIdProducto" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-12 text-sm">
                    <?php foreach ($datos['productos'] as $prod) : ?>
                        <option value="<?= (string)$prod['_id'] ?>" data-stock="<?= $prod['stock'] ?>"><?= htmlspecialchars($prod['nombreP']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="cantidad" min="1" required placeholder="Cantidad a retirar" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-12 text-sm font-bold">
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="cerrarModalSalida()" class="flex-1 h-12 rounded-xl bg-slate-100 text-slate-600 font-bold">Cancelar</button>
                    <button type="submit" class="flex-1 h-12 rounded-xl bg-rose-500 text-white font-bold shadow-lg shadow-rose-500/20">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Lógica de búsqueda en tiempo real
document.getElementById('inputBuscar').addEventListener('input', function(e) {
    const busqueda = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.card-producto');
    
    cards.forEach(card => {
        const nombre = card.querySelector('.nombre-p').textContent.toLowerCase();
        card.style.display = nombre.includes(busqueda) ? 'block' : 'none';
    });
});

// Lógica del Modal
function abrirModalSalida(id = null) {
    document.getElementById('modalSalida').classList.remove('hidden');
    if(id) document.getElementById('modalIdProducto').value = id;
}
function cerrarModalSalida() {
    document.getElementById('modalSalida').classList.add('hidden');
}
</script>

</body>
</html>