# Dockerfile unificado: PHP + Apache + MySQL + phpMyAdmin

# Etapa base con PHP y Apache
FROM php:8.3.16-apache

# Establece la zona horaria
RUN ln -sf /usr/share/zoneinfo/America/Lima /etc/localtime && \
    echo "America/Lima" > /etc/timezone

# Instala extensiones necesarias de PHP y dependencias del sistema
RUN apt-get update && apt-get install -y \
    default-mysql-server \
    supervisor \
    unzip \
    wget \
    gnupg \
    lsb-release \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Configura Apache
RUN a2enmod rewrite

# Instala phpMyAdmin manualmente
RUN mkdir -p /usr/share/phpmyadmin && \
    wget https://files.phpmyadmin.net/phpMyAdmin/5.2.1/phpMyAdmin-5.2.1-all-languages.zip && \
    unzip phpMyAdmin-5.2.1-all-languages.zip -d /usr/share/ && \
    mv /usr/share/phpMyAdmin-5.2.1-all-languages/* /usr/share/phpmyadmin && \
    rm -rf phpMyAdmin-5.2.1-all-languages.zip

# Copia el contenido de tu app PHP
COPY . /var/www/html/

# Copia configuración personalizada de MySQL y archivos SQL
COPY my.cnf /etc/mysql/conf.d/my.cnf
COPY mysql-init/ /docker-entrypoint-initdb.d/

# Copia configuración de supervisord para lanzar todos los servicios
COPY start.sh /start.sh
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN chmod +x /start.sh

# Expone solo el puerto HTTP
EXPOSE 80

# Comando de inicio: Apache + MySQL + phpMyAdmin
CMD ["/start.sh"]

