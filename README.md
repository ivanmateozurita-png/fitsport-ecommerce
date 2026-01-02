# Fitsport E-Commerce

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![Status](https://img.shields.io/badge/Status-Completed-success?style=for-the-badge)

**Fitsport** es una plataforma de comercio electrónico moderna diseñada para la venta de ropa y accesorios deportivos. Este proyecto integra buenas prácticas de desarrollo web, gestión de roles avanzada y un enfoque estricto en la calidad de software (ISO/IEC 25010).

---

## Características Principales

*   **Gestión de Roles Avanzada**:
    *   **Administrador**: Control total del sistema (Usuarios, Reportes, Configuración).
    *   **Bodeguero (Encargado)**: Gestión operativa de productos e inventario.
    *   **Cliente**: Experiencia de compra completa, historial de pedidos y perfil.
*   **Catálogo Dinámico**: Búsqueda en tiempo real y filtrado por categorías.
*   **Carrito de Compras**: Validación de stock en tiempo real y gestión de variantes.
*   **Simulación de Pagos**: Proceso de checkout interactivo con generación de órdenes.
*   **FitBot**: Asistente virtual integrado para recomendaciones de productos.
*   **Dashboard Administrativo**: Métricas de ventas y alertas de stock bajo.

---

## Stack Tecnológico

| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **Laravel** | 12.x | Framework PHP principal (Backend) |
| **PHP** | 8.2+ | Lenguaje de programación servidor |
| **MySQL** | 8.0 | Sistema de gestión de base de datos |
| **Livewire** | 3.7 | Componentes dinámicos sin JavaScript |
| **Spatie Permission** | 6.x | Gestión de roles y permisos |
| **Bootstrap** | 4.x | Framework CSS responsivo |
| **Blade** | - | Motor de plantillas de Laravel |
| **Eloquent ORM** | - | Mapeo objeto-relacional |

---

## Guía de Instalación

Este proyecto ha sido diseñado para ser fácilmente desplegable en entornos locales usando **Laragon** o cualquier servidor LAMP.

### Requisitos Previos
*   PHP 8.2+
*   Composer
*   Node.js & NPM
*   MySQL

### Pasos de Instalación (Entorno Local / Dev)

1.  **Clonar el repositorio**
    ```bash
    git clone https://github.com/tu-usuario/fitsport.git
    cd fitsport
    ```

2.  **Instalar dependencias de Backend**
    ```bash
    composer install
    ```

3.  **Instalar dependencias de Frontend**
    ```bash
    npm install
    ```

4.  **Configurar Entorno**
    *   Copia el archivo de configuración: `cp .env.example .env`
    *   Configura tu base de datos en el archivo `.env`:
        ```env
        DB_DATABASE=fitsport
        DB_USERNAME=root
        DB_PASSWORD=
        ```

5.  **Inicializar Base de Datos y Storage**
    ```bash
    php artisan key:generate
    php artisan migrate --seed
    php artisan storage:link
    ```

6.  **Ejecutar Proyecto**
    *   En una terminal: `npm run dev`
    *   En otra terminal (o usando Laragon): `php artisan serve`

---

## Credenciales de Acceso 

El sistema viene precargado con los siguientes usuarios para pruebas:

| Rol | Email | Contraseña |
| :--- | :--- | :--- |
| **Administrador** | `admin@fitsport.com` | `password` |

> **Nota:** El sistema instala por defecto solo el usuario Administrador. Para probar los roles de **Bodeguero** o **Cliente**, regístrese como un nuevo usuario y asigne los roles desde el Panel Administrativo (o base de datos).

---

## Licencia y Créditos

Desarrollado por **Mateo Zurita** para la asignatura de **Calidad de Software**.
*Instituto Superior Universitario Sucre - 2025*
