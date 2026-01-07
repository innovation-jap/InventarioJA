<?php 
// Se requiere el controlador para cualquier lógica de sesión o carga previa.
// NOTA: Se mantiene la llamada al controlador como estaba.
require_once('../Controlador/controladorProducto.php'); 

// Asumimos que la lógica de seguridad y sesión está en el controlador o en un archivo incluido previamente.
// Si no lo está, debes añadir la verificación de sesión aquí, como hiciste en otras vistas.
?>
<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="UTF-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Agregar Nuevo Producto</title>
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
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.5rem", "lg": "0.75rem", "xl": "1rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24;
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark min-h-screen flex items-start sm:items-center justify-center px-4">
<div class="w-full max-w-[640px] my-6">
    <header class="flex items-center justify-between whitespace-nowrap border border-ice/70 dark:border-gray-700 bg-startup-white dark:bg-background-dark rounded-t-xl px-6 py-3 shadow-sm">
        <div class="flex flex-wrap items-center gap-3 text-immersive-blue-black dark:text-startup-white">
            <div class="size-8 flex items-center justify-center rounded-full bg-resilient-turquoise/10 text-resilient-turquoise">
                <span class="material-symbols-outlined text-[26px]">add_box</span>
            </div>
            <div>
                <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Nuevo producto</h2>
                <p class="text-xs text-immersive-blue-black/60 dark:text-gray-400">
                    Agrega un producto al inventario.
                </p>
            </div>
        </div>
        <a href="../Controlador/controladorProducto.php?accion=listar"
           class="hidden sm:inline-flex items-center justify-center rounded-full h-8 px-3 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-200 text-xs font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
            <span class="material-symbols-outlined text-base">arrow_back</span>
        </a>
    </header>

    <main class="bg-startup-white dark:bg-background-dark rounded-b-xl shadow-sm border-x border-b border-ice/70 dark:border-gray-700">
        <div class="p-6">
            <p class="text-immersive-blue-black dark:text-startup-white text-3xl font-black leading-tight tracking-[-0.03em] mb-2">
                Registro de producto
            </p>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                Completa todos los campos para añadir un nuevo artículo al stock.
            </p>

            <form method="POST" action="../Controlador/controladorProducto.php?accion=agregar"
                  class="flex flex-col gap-6 bg-background-light dark:bg-gray-800 border border-ice dark:border-gray-700 p-6 sm:p-8 rounded-xl shadow-inner">
                <input type="hidden" name="accion" value="agregar" />

                <label class="flex flex-col w-full gap-2">
                    <p class="text-immersive-blue-black dark:text-startup-white text-xs font-semibold uppercase tracking-wider">Nombre del producto</p>
                    <input
                        type="text"
                        name="nombreP"
                        placeholder="Ej: Arroz integral, Paquete de 1kg"
                        required
                        class="form-input w-full rounded-lg text-immersive-blue-black dark:text-white 
                               focus:outline-0 focus:ring-4 focus:ring-resilient-turquoise/30 
                               border-2 border-ice dark:border-gray-600 bg-white dark:bg-gray-700 
                               h-12 px-4 text-sm font-medium placeholder:text-gray-400 dark:placeholder:text-gray-500 transition duration-200"
                    />
                </label>

                <label class="flex flex-col w-full gap-2">
                    <p class="text-immersive-blue-black dark:text-startup-white text-xs font-semibold uppercase tracking-wider">Descripción detallada</p>
                    <textarea
                        name="descripcionP"
                        placeholder="Características, marca, proveedor u otra información relevante."
                        required
                        rows="4"
                        class="form-textarea w-full rounded-lg text-immersive-blue-black dark:text-white 
                               focus:outline-0 focus:ring-4 focus:ring-resilient-turquoise/30 
                               border-2 border-ice dark:border-gray-600 bg-white dark:bg-gray-700 
                               placeholder:text-gray-400 dark:placeholder:text-gray-500 px-4 py-3 text-sm resize-none transition duration-200"
                    ></textarea>
                </label>

                <label class="flex flex-col w-full gap-2">
                    <p class="text-immersive-blue-black dark:text-startup-white text-xs font-semibold uppercase tracking-wider">Stock inicial (Unidades)</p>
                    <input
                        type="number"
                        name="stock"
                        placeholder="10"
                        min="1"
                        required
                        class="form-input w-full rounded-lg text-lg text-immersive-blue-black dark:text-white 
                               focus:outline-0 focus:ring-4 focus:ring-resilient-turquoise/30 
                               border-2 border-ice dark:border-gray-600 bg-white dark:bg-gray-700 
                               h-12 px-4 font-bold placeholder:text-gray-400 dark:placeholder:text-gray-500 transition duration-200"
                    />
                </label>

                <div class="flex flex-col-reverse sm:flex-row gap-4 justify-end pt-5 border-t border-ice dark:border-gray-700 mt-2">
                    <a href="../Controlador/controladorProducto.php?accion=listar"
                       class="w-full sm:w-auto flex items-center justify-center gap-2 h-10 px-5 rounded-full 
                              bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-200 text-sm font-bold 
                              hover:bg-gray-300 dark:hover:bg-gray-600 transition shadow-sm">
                        <span class="material-symbols-outlined text-base">close</span>
                        <span>Cancelar</span>
                    </a>
                    <button
                        type="submit"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 h-10 px-5 rounded-full 
                               bg-resilient-turquoise text-startup-white text-sm font-bold 
                               hover:bg-immersive-blue-black transition shadow-md">
                        <span class="material-symbols-outlined text-base">check_circle</span>
                        <span>Guardar producto</span>
                    </button>
                </div>
            </form>
        </div>

        <footer class="sm:hidden flex flex-col items-center justify-between gap-3 px-6 pb-4 pt-3 bg-background-light dark:bg-gray-800/60 rounded-b-xl border-t border-ice/70 dark:border-gray-700">
            <a href="../Controlador/controladorProducto.php?accion=listar"
               class="w-full flex min-w-[120px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-9 px-4 bg-startup-white dark:bg-gray-700 text-immersive-blue-black dark:text-white text-sm font-bold gap-2 border border-ice dark:border-gray-600 hover:bg-ice/70 dark:hover:bg-gray-600">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                <span class="truncate">Volver a inventario</span>
            </a>
        </footer>
    </main>
</div>
</body>
</html>