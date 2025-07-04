# phpmyadmin.Dockerfile
FROM phpmyadmin/phpmyadmin

ENV PMA_HOST=tramite-db
ENV PMA_PORT=3306
