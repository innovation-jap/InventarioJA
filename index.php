<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Donde Patty - Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
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
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-pattern {
            background-color: #f5f8f8;
            background-image: radial-gradient(#0df2f2 0.5px, transparent 0.5px), radial-gradient(#0df2f2 0.5px, #f5f8f8 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.4;
        }
        /* Animación para que aparezca suavemente */
        @keyframes fadeInSlide {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-login { animation: fadeInSlide 0.8s ease-out forwards; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark min-h-screen flex items-center justify-center relative overflow-hidden font-display">

    <div class="absolute inset-0 bg-pattern pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-[440px] px-6 py-12 animate-login">
        <div class="bg-white dark:bg-[#1a2e2e] shadow-2xl rounded-2xl overflow-hidden border border-primary/10">
            
            <div class="p-8 pb-4 text-center">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="bg-primary p-2 rounded-xl shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined !text-background-dark font-bold !text-2xl">inventory_2</span>
                    </div>
                    <h1 class="text-xl font-black tracking-tight text-slate-800 dark:text-white uppercase">Junnior Achievement</h1>
                </div>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">Bienvenido</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Ingresa tus credenciales para acceder al panel</p>
            </div>

            <form action="Controlador/procesar_login.php" method="POST" class="p-8 pt-4 space-y-6">
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1" for="nombreU">Usuario o Correo</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[20px]">person</span>
                        <input class="w-full pl-10 pr-4 py-3.5 rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all font-medium" 
                               id="nombreU" name="nombreU" placeholder="Tu usuario" type="text" required/>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center ml-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest" for="pass">Contraseña</label>
                    </div>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[20px]">lock</span>
                        <input class="w-full pl-10 pr-12 py-3.5 rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all font-medium" 
                               id="pass" name="pass" placeholder="••••••••" type="password" required/>
                        <button class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors" type="button" onclick="togglePassword()">
                            <span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input class="rounded border-slate-300 text-primary focus:ring-primary size-4" type="checkbox"/>
                        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 group-hover:text-primary transition-colors uppercase tracking-tighter">Recordarme</span>
                    </label>
                    <a class="text-[11px] font-black text-primary hover:underline underline-offset-4 uppercase tracking-tighter" href="Vista/vistaOlvidePass.php">¿Olvidó su contraseña?</a>
                </div>

                <button class="w-full bg-primary hover:bg-primary/90 text-background-dark font-black py-4 rounded-xl shadow-xl shadow-primary/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 uppercase text-sm tracking-widest" 
                        type="submit">
                    <span>Iniciar Sesión</span>
                    <span class="material-symbols-outlined text-[20px]">login</span>
                </button>

                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-100 dark:border-slate-700/50"></div>
                    </div>
                    <div class="relative flex justify-center text-[10px] font-black uppercase tracking-[0.2em]">
                        <span class="bg-white dark:bg-[#1a2e2e] px-4 text-slate-400">Acceso Seguro</span>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-8 text-center space-y-1">
            <p class="text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest">© 2026 Junnior Achievement</p>
            <p class="text-slate-400 dark:text-slate-500 text-[9px] font-bold tracking-tighter">v2.5.0-PRO | Soporte: 800-INVENTARIO</p>
        </div>
    </div>

    <div class="fixed bottom-[-10%] left-[-5%] w-[40%] h-[40%] bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="fixed top-[-10%] right-[-5%] w-[30%] h-[30%] bg-primary/10 rounded-full blur-[80px] pointer-events-none"></div>

    <script>
        // Función para mostrar/ocultar contraseña
        function togglePassword() {
            const passInput = document.getElementById('pass');
            const eyeIcon = document.getElementById('eye-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.innerText = 'visibility_off';
            } else {
                passInput.type = 'password';
                eyeIcon.innerText = 'visibility';
            }
        }
    </script>
</body>
</html>