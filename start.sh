#!/bin/bash

# 🔁 Forzar reinicialización de MySQL (⚠️ solo si estás seguro)
rm -rf /var/lib/mysql/*

# Inicializa MySQL si es la primera vez
if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "📦 Inicializando MySQL..."
    mysqld --initialize-insecure --user=mysql

    echo "⏳ Esperando que MySQL arranque para ejecutar init.sql..."
    mysqld_safe --skip-networking &
    sleep 10

    echo "⚙️ Ejecutando scripts de inicialización..."
    for f in /docker-entrypoint-initdb.d/*.sql; do
        echo "➡️ Ejecutando $f"
        mysql -u root < "$f"
    done

    echo "✅ Base de datos inicializada"
    killall mysqld
    sleep 5
fi

# Ejecuta todos los procesos (MySQL + Apache) con supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf