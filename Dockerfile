# Imagen base PHP + Apache
FROM php:8.3.16-apache

# Zona horaria
RUN ln -sf /usr/share/zoneinfo/America/Lima /etc/localtime && \
    echo "America/Lima" > /etc/timezone

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    mariadb-server \
    supervisor \
    unzip \
    wget \
    gnupg \
    lsb-release \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Habilita mod_rewrite
RUN a2enmod rewrite

# Instala phpMyAdmin
RUN mkdir -p /usr/share/phpmyadmin && \
    wget https://files.phpmyadmin.net/phpMyAdmin/5.2.1/phpMyAdmin-5.2.1-all-languages.zip && \
    unzip phpMyAdmin-5.2.1-all-languages.zip -d /usr/share/ && \
    mv /usr/share/phpMyAdmin-5.2.1-all-languages/* /usr/share/phpmyadmin && \
    rm -rf phpMyAdmin-5.2.1-all-languages.zip

# Copia app y configuración
COPY . /var/www/html/
COPY start.sh /start.sh
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./mysql-init/*.sql /mysql-init/

RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]