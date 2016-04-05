FROM szeist/phalcon-apache2
COPY / /var/www/
COPY config/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod headers
RUN a2enmod rewrite
RUN apt-get update
RUN apt-get install -y redis-server
RUN redis-server --daemonize yes
RUN apt-get install php5-redis
RUN cd /tmp && git clone --depth=1 git://github.com/phalcon/cphalcon.git && cd cphalcon/build/ && ./install
RUN echo 'extension=phalcon.so' >>  /etc/php5/apache2/php.ini
RUN service apache2 restart
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN cd /var/www && composer install --no-interaction && composer update --no-interaction
