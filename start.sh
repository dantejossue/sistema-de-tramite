#!/bin/bash

#!/bin/bash

# Inicializar MariaDB si no existe
if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "📦 Inicializando MariaDB..."
    mysql_install_db --user=mysql --ldata=/var/lib/mysql
    echo "✅ Base de datos inicializada"
fi

# Lanzar servicios con supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf