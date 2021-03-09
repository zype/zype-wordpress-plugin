FROM wordpress:5.2.4

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install XDebug
RUN pecl install -f xdebug \
&& echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)\nxdebug.mode=debug\nxdebug.start_with_request=yes" > /usr/local/etc/php/conf.d/xdebug.ini;

RUN apt-get update \
    && apt-get install -y net-tools iproute2 \
    && apt-get clean

COPY ./entrypoint.sh /tmp/entrypoint
RUN echo "\n\n" >> /tmp/entrypoint
RUN cat /usr/local/bin/docker-entrypoint.sh >> /tmp/entrypoint
RUN mv /tmp/entrypoint /usr/local/bin/docker-entrypoint.sh
RUN chmod 777 /usr/local/bin/docker-entrypoint.sh