#!/bin/bash

echo "🔒 Activando modo mantenimiento (php artisan down)..."
php artisan down || echo "⚠️  No se pudo activar mantenimiento (¿Laravel no está corriendo todavía?)"

echo "🛑 Deteniendo procesos relacionados con Laravel, Vite y Reverb..."

# Matar procesos comunes
pkill -f "php artisan serve" && echo "✅ Laravel detenido"
pkill -f "php artisan reverb:start" && echo "✅ Reverb detenido"
pkill -f "vite" && echo "✅ Vite detenido"
pkill -f "node" && echo "✅ Node detenido"

echo ""
echo "🔍 Comprobando puertos comunes..."
for port in 8000 5173 6001; do
  if lsof -i :$port &>/dev/null; then
    echo "⚠️  El puerto $port sigue ocupado. Revisa procesos activos con: lsof -i :$port"
  else
    echo "✅ Puerto $port libre"
  fi
done

rm -rf "$(pwd)/certs/*.pem"|| echo "⚠️  No se pudo eliminar los certificados (¿están en uso?)"

echo ""
echo "🔓 Reactivando la aplicación (php artisan up)..."
php artisan up || echo "⚠️  No se pudo reactivar Laravel (verifica estado manualmente)"

echo ""
echo "✅ Todos los procesos detenidos y puertos verificados."
