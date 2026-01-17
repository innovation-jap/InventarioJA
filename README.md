InventarioJA
Sistema de gestión de inventarios desarrollado bajo la arquitectura MVC (Modelo-Vista-Controlador). El proyecto está diseñado para ser ligero, modular y fácil de desplegar mediante contenedores.

🚀 Tecnologías Utilizadas
El proyecto utiliza un stack basado en PHP y herramientas de gestión de dependencias y despliegue:

Lenguaje: PHP 8.x (Principal, 93.5%)

Gestor de Dependencias: Composer

Arquitectura: MVC (Separación de lógica de negocio, datos e interfaz)

Contenerización: Docker (Uso de Dockerfile para entornos estandarizados)

Base de Datos: Configuración compatible con PDO (definida en config.php)

📁 Estructura del Proyecto
Plaintext
InventarioJA/
├── Controlador/    # Lógica de control y manejo de peticiones
├── Modelo/         # Interacción con la base de datos y entidades
├── Vista/          # Interfaz de usuario (HTML/CSS)
├── vendor/         # Dependencias instaladas vía Composer
├── config.php      # Configuración global del sistema y BD
├── index.php       # Punto de entrada de la aplicación
└── Dockerfile      # Configuración para despliegue con Docker
🛠️ Instalación y Configuración
Requisitos previos
PHP >= 8.0

Composer

Servidor web (Apache/Nginx) o Docker

Pasos para replicar el entorno:
Clonar el repositorio:

Bash
git clone https://github.com/innovation-jap/InventarioJA.git
cd InventarioJA
Instalar dependencias:

Bash
composer install
Configurar Base de Datos: Edita el archivo config.php con tus credenciales locales (Host, Usuario, Password, DB).

Levantar con Docker (Opcional):

Bash
docker build -t inventario-ja .
docker run -p 8080:80 inventario-ja
📝 Características
Gestión de productos/artículos.

Estructura escalable gracias al patrón MVC.

Listo para ser desplegado en la nube mediante Docker.
