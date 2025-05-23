#!/bin/bash

# Dirección IP LAN local
LAN_IP=$(ipconfig getifaddr en0 2>/dev/null || hostname -I | awk '{print $1}')
PORT_LARAVEL=8000
PORT_VITE=5173
PORT_REVERB=6001

# Verificar herramientas necesarias
for cmd in php pnpm; do
  if ! command -v ${cmd%% *} &>/dev/null; then
    echo "❌ Error: falta el comando '${cmd%% *}'. Instálalo antes de continuar."
    exit 1
  fi
done

echo "🚀 Levantando entorno en red local (LAN)..."
echo "🌐 IP detectada: $LAN_IP"
echo "🔗 Laravel:  http://$LAN_IP:$PORT_LARAVEL"
echo "🔗 Vite:     http://$LAN_IP:$PORT_VITE"
echo "🔗 Reverb:   ws://$LAN_IP:$PORT_REVERB"

echo ""
echo "📄 Asegúrate de cambiar la clave del archivo .env relacionada con tu IP:"
echo "    LOCAL_IP=$LAN_IP"
echo ""
read -n 1 -s -r -p "🔸 Pulsa cualquier tecla para continuar..."
echo ""

php artisan config:cache
php artisan config:clear

# Laravel backend
php artisan serve --host=0.0.0.0 --port=$PORT_LARAVEL > /dev/null 2>&1 &

# Laravel Reverb
php artisan reverb:start --host=0.0.0.0 --port=$PORT_REVERB > /dev/null 2>&1 &

# Vite frontend
pnpm run dev:vite -- --host=0.0.0.0 --port=$PORT_VITE > /dev/null 2>&1 &

echo ""
echo "🛑 Pulsa Ctrl+C para detener todos los servicios de forma segura."
echo ""


# Esperar a Ctrl+C y ejecutar dev-stop.sh
trap 'echo ""; echo "🛑 Ctrl+C detectado. Ejecutando dev-stop.sh..."; bash ./dev-stop.sh; exit 0' INT

# Esperar indefinidamente
wait
