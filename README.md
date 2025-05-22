# Visitor Management App

Aplicación web para gestión de visitantes con Laravel + Vue 3 + Vuetify + Reverb + Sanctum.

## 📦 Requisitos

- PHP 8.2+
- Node.js 18+
- MySQL 8+
- Composer
- NPM
- Laravel CLI

## 🚀 Instalación

```bash
git clone git@github.com:paveldelpozo/visitor-management.git
cd visitor-management
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
```

## 🧪 Testing

- Ejecutar tests de backend y frontend:

```bash
npm run test:all
```

También puedes usar `make test-all` si tienes `make`.

## 🧑‍💻 Desarrollo

### Paso 1: Instalar `npm-run-all`

```bash
npm install --save-dev npm-run-all
```

### Paso 2: Ejecutar Laravel + Vite en paralelo

```bash
npm run dev:full
```

o

```bash
bash ./dev.sh
```

Abre `http://localhost:8000` en el navegador.

## 🧩 Funcionalidades

- Autenticación con Laravel Sanctum
- SPA en Vue 3 con Vuetify
- Gestión de visitantes (crear, editar, buscar)
- Sistema de préstamo de auriculares
- Actualización en tiempo real con Laravel 11 + Reverb
- Registro de logs de actividad
- Exportación a CSV
- Protecciones por rol (`admin`, `user`)

## 🛠 Estructura destacada

- `resources/ts/`: Código de Vue con TypeScript
- `resources/pages/`: Páginas Vue
- `resources/components/`: Componentes reutilizables
- `resources/views/app.blade.php`: Entrada SPA
- `routes/web.php`: Carga única de la SPA
- `routes/api.php`: Rutas de la API
- `tests/`: Fixtures y pruebas unitarias

---

Hecho con ❤️ por Pavel

## 🔊 Laravel 11 + Reverb

Esta aplicación utiliza **Laravel 11 + Reverb** para actualizaciones en tiempo real.

### Desarrollo local

1. Instala Reverb (una vez):
   ```bash
   composer require laravel/reverb
   php artisan reverb:install
   ```

2. Lanza Reverb junto con Laravel y Vite:
   ```bash
   ./dev.sh
   ```

3. También puedes iniciarlo manualmente:
   ```bash
   php artisan reverb:start
   ```

Revisa que esté corriendo en `localhost:6001`. El frontend ya está configurado para conectarse automáticamente a través de Echo.

---
