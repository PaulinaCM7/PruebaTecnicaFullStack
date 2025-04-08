# Proyecto: Mini E-commerce de Medios Digitales

Este proyecto simula una plataforma donde los clientes pueden explorar, reservar y gestionar espacios publicitarios (medios) como pantallas, vallas, espectaculares, etc. Los administradores pueden gestionar usuarios, medios, reservas y ver estadísticas.

---

## Instalación y uso local

### 1. Clonar el repositorio
```bash
git clone https://github.com/PaulinaCM7/PruebaTecnicaFullStack.git
cd mini-ecommerce-medios
```

### 2. Instalar dependencias backend (Laravel)
```bash
composer install
```

### 3. Instalar dependencias frontend (Vue)
```bash
npm install
```

### 4. Configuración de la Base de Datos (MySQL)
Este proyecto utiliza MySQL como sistema de base de datos.
Puedes crear la base de datos manualmente desde tu gestor de base de datos como phpMyAdmin, MySQL Workbench, o desde la terminal:
#### Opción 1: Usando la terminal
Abre tu terminal e ingresa a MySQL:
```bash
mysql -u root -p
```
Luego ejecuta el siguiente comando:
```bash
CREATE DATABASE medios_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Opción 2: Usando phpMyAdmin
- Accede a http://localhost/phpmyadmin (si usas XAMPP).
- Haz clic en “Nueva”.
- Escribe medios_db como nombre de la base de datos.
- Selecciona cotejamiento utf8mb4_unicode_ci.
- Da clic en “Crear”.

### 5. Crear archivo `.env`
```bash
cp .env.example .env
```

### 6. Generar la clave de aplicación
```bash
php artisan key:generate
```

### 7. Ejecutar migraciones y seeders
```bash
php artisan migrate --seed
```

### 8. Compilar assets
```bash
npm run dev
```

### 9. Levantar el servidor
```bash
php artisan serve
```

Y en otro terminal:
```bash
npm run dev
```

Y en otro terminal para simular el job:
```bash
php artisan queue:work
```
---

## 👥 Roles disponibles
- **Cliente**: Puede explorar medios, reservar, y ver su historial.
- **Admin**: Acceso a dashboard administrativo, gestión de medios, usuarios y reservas.

---

## ✅ Funcionalidades clave
- Registro y login de usuarios (con autenticación Sanctum).
- Panel de administración para medios y usuarios.
- Subida de imagen de medios.
- Reservas con bloqueo de fechas y cálculo automático del total.
- Envió de correo simulado al confirmar la reserva.
- Sistema de colas y jobs para simular procesamiento de pago.
- Alerta tipo toast al crear/editar/eliminar recursos.

---

## 🧪 Pruebas
### Ejecutar pruebas
```bash
php artisan test
```
### Tipos de pruebas implementadas
- Login exitoso.
- Creación de reservas.

---

## 🛠️ Extras incluidos
- Toast personalizados para feedback del usuario.
- Calendario visual para fechas disponibles.
- Paginación y búsqueda en medios y reservas.

