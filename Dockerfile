FROM szeist/phalcon-apache2
COPY / /var/www/
COPY config/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod headers
RUN a2enmod rewrite
RUN apt-get update
RUN apt-get install -y redis-server
RUN apt-get install php5-redis
RUN cd /tmp && git clone --depth=1 git://github.com/phalcon/cphalcon.git && cd cphalcon/build/ && ./install
RUN echo 'extension=phalcon.so' >>  /etc/php5/apache2/php.ini
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN cd /var/www && composer install --no-interaction && composer update --no-interaction
ENTRYPOINT service apache2 start && service redis-server start && tail -F /var/log/redis_6379.log

