# Usa una imagen oficial de PHP con Apache
FROM php:8.3.16-apache

# Configurar la zona horaria a "America/Lima"
RUN ln -sf /usr/share/zoneinfo/America/Lima /etc/localtime && \
    echo "America/Lima" > /etc/timezone

# Instala extensiones necesarias (MySQL, GD, ZIP, etc.)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copia todos los archivos al contenedor
COPY . /var/www/html/

# Activa mod_rewrite de Apache
RUN a2enmod rewrite

# Cambia permisos si es necesario
RUN chown -R www-data:www-data /var/www/html

# Configura Apache para que escuche en el puerto que Render le pasa
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf && \
    sed -i 's/:80/:${PORT}/' /etc/apache2/sites-available/000-default.conf

# Exponer el puerto dinámico
EXPOSE ${PORT}

