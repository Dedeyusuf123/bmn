FROM php:8.2-apache

# Install ekstensi PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Aktifkan mod_rewrite TANPA restart Apache
RUN a2enmod rewrite \
    && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && rm -f /var/run/apache2/apache2.pid

WORKDIR /var/www/html

# Salin source code
COPY . /var/www/html/

# Proteksi folder internal
RUN { \
    echo '<Directory /var/www/html/app>'; \
    echo '  Require all denied'; \
    echo '</Directory>'; \
    echo '<Directory /var/www/html/models>'; \
    echo '  Require all denied'; \
    echo '</Directory>'; \
    echo '<Directory /var/www/html/routers>'; \
    echo '  Require all denied'; \
    echo '</Directory>'; \
    echo '<Directory /var/www/html/view>'; \
    echo '  Require all denied'; \
    echo '</Directory>'; \
    } > /etc/apache2/conf-available/appsecurity.conf \
    && a2enconf appsecurity \
    && rm -f /var/run/apache2/apache2.pid

# Permission upload
RUN mkdir -p /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/public/uploads

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["docker-entrypoint.sh"]