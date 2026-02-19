<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['idUsuario'])) { header("Location: ../index.php"); exit(); }

$nombreUsuario = htmlspecialchars($_SESSION['nombreU'] ?? 'Usuario');
$paginaActual = $datos['paginaActual'] ?? 1;
$totalPaginas = $datos['totalPaginas'] ?? 1;
?>
<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Junnior Achievement - Panel de Inventario</title>
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
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "1rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        @keyframes fadeInSlide { 0% { opacity: 0; transform: translateY(15px); } 100% { opacity: 1; transform: translateY(0); } }
        .animate-page { animation: fadeInSlide 0.5s ease-out forwards; }
        .nav-tabs { min-width: 320px; display: inline-flex; }
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
                        <input id="inputBuscar" class="w-80 bg-slate-100 dark:bg-background-dark/50 border-none rounded-xl pl-12 pr-4 h-11 focus:ring-2 focus:ring-primary/50 text-sm transition-all text-slate-900 dark:text-white" placeholder="Buscar por nombre..." type="text"/>
                    </div>
                </div>
                <button onclick="abrirModalAgregar()" class="bg-primary hover:bg-primary/80 text-slate-900 px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined">add</span><span class="hidden sm:inline">Nuevo producto</span>
                </button>
                <div class="flex items-center gap-3 pl-6 border-l border-slate-200 dark:border-slate-700">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sesión de</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight"><?= $nombreUsuario ?></p>
                    </div>
                    <img alt="Avatar" class="size-11 rounded-full border-2 border-primary" src="https://ui-avatars.com/api/?name=<?= urlencode($nombreUsuario) ?>&background=0df2f2&color=102222"/>
                    <a href="../Controlador/controladorCerrarSesion.php" class="p-2 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all ml-2" title="Cerrar Sesión"><span class="material-symbols-outlined">logout</span></a>
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
                    // Lógica para mostrar imagen guardada o placeholder
                    $rutaImagen = !empty($prod['imagen']) ? $prod['imagen'] : 'https://placehold.co/600x400/102222/0df2f2?text='.urlencode($prod['nombreP']);
                ?>
                <div class="tarjeta-producto bg-white dark:bg-background-dark/80 rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden group hover:shadow-2xl transition-all duration-300 flex flex-col">
                    <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="<?= $rutaImagen ?>"/>
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary/90 text-slate-900 text-[10px] font-black px-3 py-1.5 rounded-lg shadow-xl uppercase tracking-widest"><?= htmlspecialchars($prod['almacen'] ?? 'General') ?></span>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 leading-tight"><?= htmlspecialchars($prod['nombreP']) ?></h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 line-clamp-3"><?= htmlspecialchars($prod['descripcionP'] ?? 'Sin descripción.') ?></p>
                        <div class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-800">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col"><span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Stock</span><span class="text-2xl font-black <?= $stock <= 5 ? 'text-rose-500 animate-pulse' : 'text-slate-900 dark:text-white' ?>"><?= $stock ?></span></div>
                                <div class="flex items-center gap-1">
                                    <button onclick="abrirModalSalida('<?= $idStr ?>')" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-rose-500 hover:bg-rose-500 hover:text-white transition-all"><span class="material-symbols-outlined">trending_down</span></button>
                                    <button onclick="abrirModalDevolucion('<?= $idStr ?>', '<?= htmlspecialchars($prod['nombreP']) ?>')" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all"><span class="material-symbols-outlined">undo</span></button>
                                    <button onclick="abrirModalEditar('<?= $idStr ?>', '<?= htmlspecialchars($prod['nombreP']) ?>', '<?= htmlspecialchars($prod['descripcionP'] ?? '') ?>', '<?= $stock ?>', '<?= htmlspecialchars($prod['almacen'] ?? 'Almacén 1') ?>', '<?= $prod['imagen'] ?? '' ?>')" 
        class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-primary transition-all">
    <span class="material-symbols-outlined">edit</span>
</button>
                                    <a href="../Controlador/controladorProducto.php?accion=eliminar&id=<?= urlencode($idStr) ?>" onclick="return confirm('¿Eliminar?');" class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 hover:bg-rose-600 hover:text-white transition-all"><span class="material-symbols-outlined">delete</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-white dark:bg-[#152a2a] border-t border-slate-100 dark:border-slate-800 py-8 mt-10">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 lg:px-12 flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
            <div class="flex items-center gap-8"><button onclick="abrirModalSalida()" class="text-slate-600 dark:text-slate-400 hover:text-rose-500 font-bold flex items-center gap-2 transition-colors uppercase text-xs tracking-widest"><span class="material-symbols-outlined">logout</span> REGISTRAR SALIDA</button><a href="../Controlador/controladorProducto.php?accion=movimientos" class="text-slate-600 dark:text-slate-400 hover:text-primary font-bold flex items-center gap-2 transition-colors uppercase text-xs tracking-widest"><span class="material-symbols-outlined">history</span> HISTORIAL</a></div>
            <div class="flex items-center gap-4"><p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">© 2026 Sistema Inventory</p><a href="../Controlador/controladorCerrarSesion.php" class="text-rose-500 text-[10px] font-black uppercase tracking-widest hover:underline">CERRAR SESIÓN</a></div>
        </div>
    </footer>
</div>

<div id="modalAgregar" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="cerrarModalAgregar()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white dark:bg-background-dark p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-2xl">
            <div class="flex items-center gap-4 mb-8">
                <div class="bg-primary size-12 rounded-2xl flex items-center justify-center text-slate-900 shadow-lg shadow-primary/20"><span class="material-symbols-outlined !text-2xl font-bold">add_box</span></div>
                <div><h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Nuevo Producto</h3><p class="text-xs text-slate-500">Añadir al inventario.</p></div>
            </div>
            
            <form method="POST" action="../Controlador/controladorProducto.php?accion=agregar" enctype="multipart/form-data" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nombre</label><input type="text" name="nombreP" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-11 px-4 text-sm text-slate-900 dark:text-white"></div>
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Ubicación</label><select name="almacen" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-11 px-4 text-sm text-slate-900 dark:text-white"><option value="Almacén 1">Almacén 1</option><option value="Almacén 2">Almacén 2</option><option value="Sótano">Sótano</option></select></div>
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Stock inicial</label><input type="number" name="stock" min="1" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-11 px-4 text-lg font-black text-primary"></div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Imagen del producto</label>
                        <div id="previewContainer" class="relative w-full h-40 rounded-2xl bg-slate-50 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden">
                            <img id="imgPreview" src="" class="hidden w-full h-full object-cover">
                            <div id="previewPlaceholder" class="text-center p-4">
                                <span class="material-symbols-outlined text-slate-300 !text-4xl">add_a_photo</span>
                                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase">Click para subir</p>
                            </div>
                            <input type="file" name="imagen" id="inputImagen" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>
                </div>

                <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Descripción</label><textarea name="descripcionP" rows="2" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 text-sm text-slate-900 dark:text-white resize-none"></textarea></div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="cerrarModalAgregar()" class="flex-1 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 font-bold hover:bg-slate-200 transition-all">CANCELAR</button>
                    <button type="submit" class="flex-1 h-14 rounded-2xl bg-primary text-slate-900 font-black shadow-xl shadow-primary/20 transition-all">GUARDAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalSalida" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="cerrarModalSalida()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white dark:bg-background-dark p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-2xl">
            <div class="flex items-center gap-4 mb-8"><div class="bg-rose-500 size-12 rounded-2xl flex items-center justify-center text-white"><span class="material-symbols-outlined font-bold">arrow_downward</span></div><div><h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Baja de Stock</h3></div></div>
            <form method="POST" action="../Controlador/controladorProducto.php?accion=salida" class="space-y-6">
                <select name="idProducto" id="modalIdProducto" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-14 px-4 text-sm text-slate-900 dark:text-white">
                    <option value="">Selecciona...</option>
                    <?php if (!empty($datos['productos'])) : foreach ($datos['productos'] as $p) : ?>
                    <option value="<?= (string)$p['_id'] ?>" data-stock="<?= $p['stock'] ?>"><?= htmlspecialchars($p['nombreP']) ?></option>
                    <?php endforeach; endif; ?>
                </select>
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="cantidad" min="1" required placeholder="Cantidad" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-14 px-4 text-lg font-black text-rose-500">
                    <input type="text" id="modalStockLabel" disabled placeholder="Stock" class="w-full bg-slate-100 dark:bg-slate-900/50 rounded-xl h-14 px-4 text-sm text-slate-400 font-bold">
                </div>
                <div class="flex gap-3"><button type="button" onclick="cerrarModalSalida()" class="flex-1 h-14 rounded-2xl bg-slate-100 text-slate-600 font-bold">CANCELAR</button><button type="submit" class="flex-1 h-14 rounded-2xl bg-rose-500 text-white font-black">CONFIRMAR</button></div>
            </form>
        </div>
    </div>
</div>

<div id="modalDevolucion" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="cerrarModalDevolucion()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white dark:bg-background-dark p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-2xl">
            <div class="flex items-center gap-4 mb-8"><div class="bg-emerald-500 size-12 rounded-2xl flex items-center justify-center text-white"><span class="material-symbols-outlined font-bold">undo</span></div><div><h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Devolución</h3></div></div>
            <form method="POST" action="../Controlador/controladorProducto.php?accion=devolucion" class="space-y-6">
                <input type="hidden" name="idProducto" id="modalDevId">
                <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl text-center"><p id="modalDevNombre" class="font-bold text-slate-900 dark:text-white text-lg">---</p></div>
                <input type="number" name="cantidad" min="1" required placeholder="Cantidad a reponer" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-14 px-4 text-lg font-black text-emerald-500">
                <div class="flex gap-3"><button type="button" onclick="cerrarModalDevolucion()" class="flex-1 h-14 rounded-2xl bg-slate-100 text-slate-600 font-bold">CANCELAR</button><button type="submit" class="flex-1 h-14 rounded-2xl bg-emerald-500 text-white font-black">CONFIRMAR</button></div>
            </form>
        </div>
    </div>
</div>

<div id="modalEditar" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="cerrarModalEditar()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white dark:bg-background-dark p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-2xl">
            <div class="flex items-center gap-4 mb-8">
                <div class="bg-primary size-12 rounded-2xl flex items-center justify-center text-slate-900 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined !text-2xl font-bold">edit_square</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Editar Producto</h3>
                    <p class="text-xs text-slate-500">Actualizar información en la nube.</p>
                </div>
            </div>
            
            <form method="POST" action="../Controlador/controladorProducto.php?accion=editar" enctype="multipart/form-data" class="space-y-5">
                <input type="hidden" name="idProducto" id="edit_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nombre</label>
                            <input type="text" name="nombreP" id="edit_nombre" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-11 px-4 text-sm text-slate-900 dark:text-white">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Ubicación</label>
                            <select name="almacen" id="edit_almacen" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-11 px-4 text-sm text-slate-900 dark:text-white">
                                <option value="Almacén 1">Almacén 1</option>
                                <option value="Almacén 2">Almacén 2</option>
                                <option value="Sótano">Sótano</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Stock</label>
                            <input type="number" name="stock" id="edit_stock" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl h-11 px-4 text-lg font-black text-primary">
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nueva Imagen (Opcional)</label>
                        <div class="relative w-full h-40 rounded-2xl bg-slate-50 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden">
                            <img id="edit_imgPreview" src="" class="hidden w-full h-full object-cover">
                            <div id="edit_previewPlaceholder" class="text-center p-4">
                                <span class="material-symbols-outlined text-slate-300 !text-4xl">cloud_upload</span>
                                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase">Subir a Cloudinary</p>
                            </div>
                            <input type="file" name="imagen" id="edit_inputImagen" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Descripción</label>
                    <textarea name="descripcionP" id="edit_descripcion" rows="2" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 text-sm text-slate-900 dark:text-white resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="cerrarModalEditar()" class="flex-1 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 font-bold hover:bg-slate-200 transition-all">CANCELAR</button>
                    <button type="submit" class="flex-1 h-14 rounded-2xl bg-primary text-slate-900 font-black shadow-xl shadow-primary/20 transition-all">ACTUALIZAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// FUNCIONES MODALES
function abrirModalAgregar() { document.getElementById('modalAgregar').classList.remove('hidden'); }
function cerrarModalAgregar() { 
    document.getElementById('modalAgregar').classList.add('hidden');
    // Limpiar preview al cerrar
    document.getElementById('imgPreview').src = "";
    document.getElementById('imgPreview').classList.add('hidden');
    document.getElementById('previewPlaceholder').classList.remove('hidden');
}

// PREVISUALIZACIÓN DE IMAGEN
document.getElementById('inputImagen').addEventListener('change', function(e) {
    const reader = new FileReader();
    const preview = document.getElementById('imgPreview');
    const placeholder = document.getElementById('previewPlaceholder');
    
    reader.onload = function() {
        preview.src = reader.result;
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
    }
    
    if(e.target.files[0]) {
        reader.readAsDataURL(e.target.files[0]);
    }
});
// MODAL DE EDITAR
function abrirModalEditar(id, nombre, desc, stock, almacen, imagen) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_descripcion').value = desc;
    document.getElementById('edit_stock').value = stock;
    document.getElementById('edit_almacen').value = almacen;
    
    const preview = document.getElementById('edit_imgPreview');
    const placeholder = document.getElementById('edit_previewPlaceholder');
    
    if(imagen) {
        preview.src = imagen;
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
    } else {
        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');
    }
    
    document.getElementById('modalEditar').classList.remove('hidden');
}

function cerrarModalEditar() {
    document.getElementById('modalEditar').classList.add('hidden');
}

// Previsualización para el modal de editar
document.getElementById('edit_inputImagen').addEventListener('change', function(e) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('edit_imgPreview');
        preview.src = reader.result;
        preview.classList.remove('hidden');
        document.getElementById('edit_previewPlaceholder').classList.add('hidden');
    }
    if(e.target.files[0]) reader.readAsDataURL(e.target.files[0]);
});

// Lógica de Salida
const modalS = document.getElementById('modalSalida');
const selProd = document.getElementById('modalIdProducto');
const labStock = document.getElementById('modalStockLabel');
function abrirModalSalida(id = null) { modalS.classList.remove('hidden'); if (id) { selProd.value = id; actualizarLabel(); } }
function cerrarModalSalida() { modalS.classList.add('hidden'); }
function actualizarLabel() { const opt = selProd.options[selProd.selectedIndex]; labStock.value = opt.getAttribute('data-stock') || '0'; }
selProd.addEventListener('change', actualizarLabel);

// Lógica de Devolución
const modalD = document.getElementById('modalDevolucion');
const devId = document.getElementById('modalDevId');
const devNombre = document.getElementById('modalDevNombre');
function abrirModalDevolucion(id, nombre) { modalD.classList.remove('hidden'); devId.value = id; devNombre.innerText = nombre; }
function cerrarModalDevolucion() { modalD.classList.add('hidden'); }

// BÚSQUEDA REALTIME
document.getElementById('inputBuscar').addEventListener('input', function () {
    const termino = this.value.toLowerCase().trim();
    document.querySelectorAll('.tarjeta-producto').forEach(tarjeta => {
        const nombre = tarjeta.querySelector('h3').textContent.toLowerCase();
        if (nombre.includes(termino)) {
            tarjeta.style.display = 'flex';
            tarjeta.classList.remove('animate-page');
            void tarjeta.offsetWidth;
            tarjeta.classList.add('animate-page');
        } else { tarjeta.style.display = 'none'; }
    });
});

document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { cerrarModalAgregar(); cerrarModalSalida(); cerrarModalDevolucion(); } });
</script>
</body>
</html>