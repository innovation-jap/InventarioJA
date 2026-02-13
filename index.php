<!DOCTYPE html>
<html lang="es" class="dark"> <head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Donde Patty - Acceso al Sistema</title>
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
        @keyframes fadeInSlide {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-login {
            animation: fadeInSlide 0.8s ease-out forwards;
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-background-dark min-h-screen text-slate-100 flex items-center justify-center p-4">

<div class="animate-login w-full max-w-5xl">
    <div class="grid grid-cols-1 md:grid-cols-2 rounded-[2.5rem] shadow-2xl overflow-hidden bg-[#152a2a] border border-primary/10">

        <div class="relative hidden md:flex flex-col justify-between p-12 bg-gradient-to-br from-[#1a3535] to-background-dark">
            <div class="z-10">
                <div class="flex items-center gap-3">
                    <div class="bg-primary size-10 rounded-xl flex items-center justify-center text-slate-900 shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined !text-2xl font-bold">inventory_2</span>
                    </div>
                    <h2 class="text-xl font-black tracking-tight text-white uppercase">Donde Patty</h2>
                </div>
                <h1 class="text-4xl font-black text-white mt-10 leading-tight">
                    Gestión de <br>
                    <span class="text-primary">Inventario Pro</span>
                </h1>
                <p class="text-slate-400 mt-4 text-lg font-medium">
                    Control absoluto de stock, movimientos y auditoría en tiempo real.
                </p>
            </div>

            <div class="relative w-full aspect-square opacity-20 group">
                <div class="absolute inset-0 bg-primary/20 blur-[100px] rounded-full"></div>
                <div class="w-full h-full flex items-center justify-center">
                    <span class="material-symbols-outlined !text-[180px] text-primary">analytics</span>
                </div>
            </div>

            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">
                © 2026 Junnior Achievement
            </p>
        </div>

        <div class="flex flex-col justify-center p-8 sm:p-16 bg-white dark:bg-[#152a2a]">
            <div class="mb-10 text-center md:text-left">
                <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Bienvenido</h2>
                <p class="text-slate-500 dark:text-slate-400 font-medium mt-2">Ingresa tus credenciales de acceso</p>
            </div>

            <form action="Controlador/procesar_login.php" method="post" class="flex flex-col gap-5">
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1" for="nombreU">Usuario</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">person</span>
                        <input 
                            class="w-full bg-slate-50 dark:bg-background-dark border-none rounded-2xl pl-12 pr-4 h-14 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                            type="text" id="nombreU" name="nombreU" required placeholder="Nombre de usuario"/>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest" for="pass">Contraseña</label>
                        <a href="Vista/vistaOlvidePass.php" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline">¿Olvidaste?</a>
                    </div>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">lock</span>
                        <input 
                            class="w-full bg-slate-50 dark:bg-background-dark border-none rounded-2xl pl-12 pr-4 h-14 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                            type="password" id="pass" name="pass" required placeholder="••••••••"/>
                    </div>
                </div>

                <button type="submit"
                        class="mt-4 w-full h-14 rounded-2xl bg-primary text-slate-900 font-black text-sm uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <span>Acceder al Sistema</span>
                    <span class="material-symbols-outlined !text-xl">login</span>
                </button>
            </form>

            <div class="mt-12 text-center">
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-tighter">
                    Acceso restringido para personal autorizado
                </p>
            </div>
        </div>

    </div>
</div>

</body>
</html>