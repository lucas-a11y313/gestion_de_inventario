# GEMINI Project Context: Sistema de Inventario (Laravel + Docker)

## 1. Resumen del Proyecto

Este repositorio contiene el código fuente de **"sistemaInventarioIoT"**, una aplicación web para la gestión de inventario. Está construida con el framework **Laravel (PHP)** y diseñada para ser ejecutada en un entorno containerizado con **Docker**.

La arquitectura de la aplicación sigue el patrón **Modelo-Vista-Controlador (MVC)** de Laravel:
- **Modelos:** Definen la estructura de datos y la lógica de negocio (ej. `Producto.php`, `Venta.php`, `Cliente.php`). Se encuentran en `sistemaInventarioIoT/app/Models/`.
- **Vistas:** Construidas con Blade, el motor de plantillas de Laravel. Se encuentran en `sistemaInventarioIoT/resources/views/`.
- **Controladores:** Gestionan las solicitudes del usuario y la interacción entre modelos y vistas. Se encuentran en `sistemaInventarioIoT/app/Http/Controllers/`.

El sistema utiliza **MySQL/MariaDB** como base de datos y **phpMyAdmin** para la gestión de la misma. El frontend está construido con **Vite** y **Tailwind CSS**.

## 2. Tecnologías Clave

- **Backend:** PHP 8.2, Laravel 11
- **Frontend:** Vite, Tailwind CSS, JavaScript
- **Base de Datos:** MySQL / MariaDB
- **Contenerización:** Docker, Docker Compose
- **Dependencias Notables:**
    - `spatie/laravel-permission`: Para la gestión de roles y permisos.
    - `barryvdh/laravel-dompdf`: Para la generación de reportes en PDF.

## 3. Construcción y Ejecución

La aplicación está diseñada para ser gestionada completamente a través de Docker Compose. El archivo `DOCKER_ACCESS.md` es la guía de referencia principal para interactuar con los contenedores.

### 3.1. Iniciar el Entorno
Para levantar todos los servicios (aplicación, base de datos, phpMyAdmin):
```bash
docker-compose up -d
```

### 3.2. Detener el Entorno
Para detener todos los servicios:
```bash
docker-compose down
```

### 3.3. Acceso a los Servicios
- **Aplicación Laravel:** [http://localhost:8000](http://localhost:8000)
- **phpMyAdmin:** [http://localhost:8091](http://localhost:8091) (Credenciales en `.env`)

## 4. Comandos de Desarrollo y Mantenimiento

Todos los comandos de `artisan`, `composer` o `npm` deben ejecutarse dentro del contenedor de la aplicación para asegurar el entorno correcto.

### 4.1. Acceder al Contenedor de la Aplicación
```bash
docker exec -it laravel_app bash
```

### 4.2. Comandos Comunes (dentro del contenedor)

Una vez dentro del contenedor (`laravel_app`), puedes usar los siguientes comandos:

- **Instalar dependencias PHP:**
  ```bash
  composer install
  ```
- **Instalar dependencias JS y compilar para desarrollo:**
  ```bash
  npm install
  npm run dev
  ```
- **Ejecutar migraciones de la base de datos:**
  ```bash
  php artisan migrate
  ```
- **Limpiar la caché de Laravel (muy útil durante el desarrollo):**
  ```bash
  php artisan optimize:clear
  ```
- **Verificar el estado de las migraciones:**
  ```bash
  php artisan migrate:status
  ```

Para una lista completa de comandos y utilidades, consulta el archivo `DOCKER_ACCESS.md`.
