FROM mysql:8.0

ENV MYSQL_ROOT_PASSWORD=admin
ENV MYSQL_DATABASE=sis_tramite
ENV MYSQL_PASSWORD=admin

COPY ./mysql-init/*.sql /docker-entrypoint-initdb.d/