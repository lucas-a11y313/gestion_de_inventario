# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel-based inventory management system with IoT integration (Sistema Inventario IoT). The application manages products, purchases, sales, categories, brands, clients, and suppliers with role-based permissions.

## Development Commands

### Start Development Environment
```bash
composer run dev  # Starts server, queue, logs, and vite concurrently
```

### Individual Services
```bash
php artisan serve        # Start Laravel development server
npm run dev             # Start Vite development server with hot reload
npm run build           # Build assets for production
php artisan queue:listen # Start queue worker
php artisan pail        # Start log monitoring
```

### Testing
```bash
php artisan test        # Run Laravel tests
vendor/bin/phpunit      # Alternative test runner
```

### Database
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Fresh migration with seeders
php artisan db:seed             # Run seeders only
```

### Code Quality
```bash
vendor/bin/pint         # Laravel Pint code formatting
php artisan ide-helper:generate # Generate IDE helper files
```

## Architecture

### Core Models and Relationships
- **Producto**: Core product model with many-to-many relationships to Compra, Venta, and Categoria. Belongs to Marca
- **Compra/Venta**: Purchase/Sale models with pivot tables storing quantity, prices, and timestamps
- **User**: Authentication with Spatie Laravel Permission for role-based access control
- **Categoria, Marca, Cliente, Proveedor**: Supporting entities for product management

### Database
- Uses SQLite for development (`database/database.sqlite`)
- Migrations follow Laravel naming conventions
- Pivot tables: `compra_producto`, `venta_producto`, `categoria_producto`

### Controllers
Resource controllers for main entities (productos, compras, ventas, etc.) using Laravel's `Route::resources()`. Controllers follow naming pattern: `{entity}Controller`.

### Views Structure
- Blade templates organized by feature in `resources/views/`
- Key directories: `producto/`, `compra/`, `venta/`, `categoria/`, `marca/`, `cliente/`, `proveedor/`, `user/`, `role/`
- Special inventory views: `InventarioBP/`, `InventarioInsumos/`
- Main template: `template.blade.php`

### Frontend
- **CSS Framework**: TailwindCSS with custom configuration
- **Build Tool**: Vite with Laravel plugin
- **Assets**: Located in `resources/css/app.css` and `resources/js/app.js`

### Key Features
- **Image Upload**: Products support image uploads stored in `storage/app/public/productos/`
- **PDF Generation**: Uses barryvdh/laravel-dompdf for inventory reports
- **Permissions**: Spatie Laravel Permission package for granular access control
- **Soft Deletes**: Available on main models for data integrity

### Routes
- Resource routes for CRUD operations on main entities
- Custom routes for PDF generation (`productos/inventario/pdf`)
- Authentication routes (`/login`, `/logout`)
- Fallback route for 404 handling

## Development Notes

### File Upload Handling
Products use `hanbleUploadImage()` method for image processing. Images are stored with timestamp prefixes for uniqueness.

### Authentication
Simple login/logout system. Users have roles and permissions managed through Spatie package.

### Database Relationships
Complex many-to-many relationships between products, purchases, and sales with additional pivot data (quantities, prices, discounts).

### Code Style
Follow Laravel conventions and PSR standards. Use Laravel Pint for automatic formatting.