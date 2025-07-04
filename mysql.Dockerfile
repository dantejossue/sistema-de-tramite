FROM mysql:8.0

ENV MYSQL_ROOT_PASSWORD=admin
ENV MYSQL_DATABASE=sis_tramite

COPY ./mysql-init/*.sql /docker-entrypoint-initdb.d/
COPY ./my.cnf /etc/mysql/conf.d/my.cnf   

EXPOSE 3306