FROM php:7.2.2-apache
RUN docker-php-ext-install mysqli
COPY web/conf/apache2.conf /etc/apache2/apache2.conf
