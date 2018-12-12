FROM mattrayner/lamp:latest-1604-php7
MAINTAINER Ian Lessa
COPY ./ /app/

RUN echo "cd /app && composer install -vvv && cd /" >> /create_mysql_users.sh
RUN echo "service mysql start" >> /create_mysql_users.sh
RUN echo "mysql < /app/database_up.sql" >> /create_mysql_users.sh

EXPOSE 80