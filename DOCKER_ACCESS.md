# Guía de Acceso a Contenedores Docker

## Acceso al Contenedor Laravel

### 1. Acceso con bash (recomendado)
```bash
docker exec -it laravel_app bash
```

### 2. Acceso con sh (si bash no está disponible)
```bash
docker exec -it laravel_app sh
```

### 3. Ejecutar comandos directos sin entrar al contenedor
```bash
docker exec laravel_app php artisan migrate
docker exec laravel_app composer install
docker exec laravel_app php artisan cache:clear
docker exec laravel_app php artisan config:clear
docker exec laravel_app php artisan route:clear
docker exec laravel_app php artisan view:clear
```

## Acceso al Contenedor MySQL

### 1. Acceder al contenedor de la base de datos
```bash
docker exec -it mariadb-db-1 bash
```

### 2. Acceder directamente a MySQL
```bash
docker exec -it mariadb-db-1 mysql -uroot -ppassword dblaboratorio
```

### 3. Ejecutar consultas SQL directas
```bash
docker exec mariadb-db-1 mysql -uroot -ppassword -e "USE dblaboratorio; SHOW TABLES;"
docker exec mariadb-db-1 mysql -uroot -ppassword -e "USE dblaboratorio; SELECT * FROM migrations;"
```

## Comandos útiles dentro del contenedor Laravel

Una vez dentro con `docker exec -it laravel_app bash`, estarás en `/var/www/html` y podrás ejecutar:

### Migraciones
```bash
php artisan migrate                    # Ejecutar migraciones pendientes
php artisan migrate:status             # Ver estado de migraciones
php artisan migrate:rollback           # Revertir última migración
php artisan migrate:fresh              # Eliminar todas las tablas y migrar de nuevo
php artisan migrate:fresh --seed       # Migrar y ejecutar seeders
```

### Caché
```bash
php artisan cache:clear                # Limpiar caché de aplicación
php artisan config:clear               # Limpiar caché de configuración
php artisan route:clear                # Limpiar caché de rutas
php artisan view:clear                 # Limpiar caché de vistas
php artisan optimize:clear             # Limpiar todas las cachés
```

### Composer
```bash
composer install                       # Instalar dependencias
composer update                        # Actualizar dependencias
composer dump-autoload                 # Regenerar autoload
```

### Otros comandos útiles
```bash
php artisan tinker                     # Console interactiva de Laravel
php artisan serve                      # Iniciar servidor de desarrollo
php artisan queue:work                 # Ejecutar worker de colas
php artisan schedule:work              # Ejecutar scheduler
php artisan list                       # Ver todos los comandos disponibles
```

## Gestión de Contenedores

### Ver contenedores en ejecución
```bash
docker ps
```

### Ver todos los contenedores (incluyendo detenidos)
```bash
docker ps -a
```

### Iniciar contenedores
```bash
docker-compose up -d
```

### Detener contenedores
```bash
docker-compose down
```

### Reiniciar un contenedor específico
```bash
docker restart laravel_app
docker restart mariadb-db-1
```

### Ver logs de un contenedor
```bash
docker logs laravel_app
docker logs mariadb-db-1
docker logs -f laravel_app              # Seguir logs en tiempo real
```

## Información de Conexión

### Base de Datos
- **Host**: db (dentro de Docker) o localhost:3306 (desde el host)
- **Database**: dblaboratorio
- **User**: root
- **Password**: password

### PHPMyAdmin
- **URL**: http://localhost:8091
- **User**: root
- **Password**: password

### Laravel App
- **URL**: http://localhost:8000
