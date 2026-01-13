<?php 
// Lógica de inicio de sesión
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit();
}

// Requerimos el controlador para cargar $datos['productos']
// Nota: El controlador ahora maneja la conexión a MongoDB Atlas
require_once(__DIR__ . '/../Controlador/controladorProducto.php');

$datos['error'] = $datos['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="UTF-8" />
    <title>Sistema de Inventario - Registrar Salida</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
<body class="font-display bg-background-light dark:bg-background-dark min-h-screen flex items-start sm:items-center justify-center px-4">
<div class="w-full max-w-[640px] my-6">
    <header class="flex items-center justify-between whitespace-nowrap border border-ice/70 dark:border-gray-700 bg-startup-white dark:bg-background-dark rounded-t-xl px-6 py-3 shadow-sm">
        <div class="flex flex-wrap items-center gap-3 text-immersive-blue-black dark:text-startup-white">
            <div class="size-8 flex items-center justify-center rounded-full bg-resilient-turquoise/10 text-red-600 dark:text-red-400">
                <span class="material-symbols-outlined text-[26px]">arrow_downward</span>
            </div>
            <div>
                <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Registrar salida</h2>
                <p class="text-xs text-immersive-blue-black/60 dark:text-gray-400">Actualiza el stock restando unidades.</p>
            </div>
        </div>
        <a href="../Controlador/controladorProducto.php?accion=listar"
           class="hidden sm:inline-flex items-center justify-center rounded-full h-8 px-3 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-200 text-xs font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
            <span class="material-symbols-outlined text-base">arrow_back</span>
        </a>
    </header>

    <main class="bg-startup-white dark:bg-background-dark rounded-b-xl shadow-sm border-x border-b border-ice/70 dark:border-gray-700">
        <div class="p-6">
            <p class="text-immersive-blue-black dark:text-startup-white text-3xl font-black leading-tight tracking-[-0.03em] mb-2">Salida de Inventario</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Selecciona el producto y la cantidad a retirar del stock.</p>

            <div class="flex flex-col gap-6 bg-background-light dark:bg-gray-800 border border-ice dark:border-gray-700 p-6 sm:p-8 rounded-xl shadow-inner">
                
                <?php if (!empty($datos['error'])) : ?>
                    <div class="flex items-start gap-3 rounded-lg bg-red-100 dark:bg-red-900/30 p-4 border border-red-300 dark:border-red-800">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-400 flex-shrink-0">error</span>
                        <p class="text-red-700 dark:text-red-300 text-sm font-medium"><?= htmlspecialchars($datos['error']) ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" action="../Controlador/controladorProducto.php?accion=salida" class="flex flex-col gap-6">
                    <input type="hidden" name="accion" value="salida" />

                    <label class="flex flex-col w-full gap-2">
                        <p class="text-immersive-blue-black dark:text-startup-white text-xs font-semibold uppercase tracking-wider">Producto a retirar</p>
                        <select
                            name="idProducto"
                            id="idProducto"
                            required
                            class="form-select w-full rounded-lg text-immersive-blue-black dark:text-white focus:ring-4 focus:ring-resilient-turquoise/30 border-2 border-ice dark:border-gray-600 bg-white dark:bg-gray-700 h-12 px-4 text-sm font-medium transition duration-200"
                        >
                            <option value="">Seleccionar un producto...</option>
                            <?php if (!empty($datos['productos'])) : ?>
                                <?php foreach ($datos['productos'] as $prod) : 
                                    $idStr = (string)$prod['_id']; // MongoDB ObjectID a string
                                ?>
                                    <option 
                                        value="<?= htmlspecialchars($idStr) ?>" 
                                        data-stock="<?= htmlspecialchars($prod['stock'] ?? 0) ?>"
                                        <?= ($prod['stock'] ?? 0) == 0 ? 'disabled class="text-red-500 bg-red-50 dark:bg-red-900"' : '' ?>
                                    >
                                        <?= htmlspecialchars($prod['nombreP'] ?? 'Producto sin nombre') ?> 
                                        (Stock: <?= htmlspecialchars($prod['stock'] ?? 0) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </label>

                    <div class="flex flex-col sm:flex-row gap-4 w-full">
                        <label class="flex flex-col flex-1 gap-2">
                            <p class="text-immersive-blue-black dark:text-startup-white text-xs font-semibold uppercase tracking-wider">Cantidad a retirar</p>
                            <input
                                type="number"
                                name="cantidad"
                                id="cantidad"
                                placeholder="0"
                                min="1"
                                required
                                class="form-input w-full rounded-lg text-lg text-immersive-blue-black dark:text-white focus:ring-4 focus:ring-resilient-turquoise/30 border-2 border-ice dark:border-gray-600 bg-white dark:bg-gray-700 h-12 px-4 font-bold transition duration-200"
                            />
                        </label>
                        <label class="flex flex-col flex-1 gap-2">
                            <p class="text-immersive-blue-black dark:text-startup-white text-xs font-semibold uppercase tracking-wider">Stock actual</p>
                            <input
                                type="text"
                                id="stockActual"
                                disabled
                                placeholder="Selecciona un producto"
                                class="form-input w-full rounded-lg text-gray-500 dark:text-gray-400 border-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800/50 h-12 px-4 text-sm cursor-not-allowed"
                                value=""
                            />
                        </label>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row gap-4 justify-end pt-5 border-t border-ice dark:border-gray-700 mt-2">
                        <a href="../Controlador/controladorProducto.php?accion=listar"
                           class="w-full sm:w-auto flex items-center justify-center gap-2 h-10 px-5 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-200 text-sm font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition shadow-sm">
                            <span class="material-symbols-outlined text-base">close</span>
                            <span>Cancelar</span>
                        </a>
                        <button
                            type="submit"
                            class="w-full sm:w-auto flex items-center justify-center gap-2 h-10 px-5 rounded-full bg-resilient-turquoise text-startup-white text-sm font-bold hover:bg-immersive-blue-black transition shadow-md">
                            <span class="material-symbols-outlined text-base">check_circle</span>
                            <span>Confirmar salida</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
    // Lógica JavaScript para actualizar stock
    document.getElementById('idProducto').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock') || '';
        document.getElementById('stockActual').value = stock;
    });
</script>
</body>
</html>