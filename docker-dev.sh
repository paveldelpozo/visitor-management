#!/bin/bash

echo "🚀 Levantando entorno Docker para desarrollo (Laravel, MySQL, Redis, Reverb)..."

docker-compose up -d --build

echo "✅ Contenedores activos:"
echo "   - Laravel:       http://localhost:8000"
echo "   - MySQL:         localhost:3306 (user: visitor / pass: visitor)"
echo "   - Redis:         localhost:6379"
echo "   - Reverb socket: ws://localhost:6001"
echo ""
echo "ℹ️  Para entrar en el contenedor Laravel:"
echo "   docker exec -it visitor-app bash"