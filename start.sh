#!/bin/bash

# Inicializa MySQL si es la primera vez
if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "📦 Inicializando MySQL..."
    mysqld --initialize-insecure
fi

# Ejecuta todos los procesos (MySQL + Apache) con supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf