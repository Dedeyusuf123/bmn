FROM php:8.2-apache

# Ekstensi PHP yang dibutuhkan aplikasi (mysqli + pdo_mysql)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Aktifkan mod_rewrite & izinkan .htaccess (DirectoryIndex, dsb.)
RUN a2enmod rewrite \
    && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Salin seluruh source aplikasi
COPY . /var/www/html/

# Batasi akses langsung ke folder/berkas internal (hanya boleh diakses lewat include PHP)
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
    echo '<Files "koneksi.php">'; \
    echo '  Require all denied'; \
    echo '</Files>'; \
    echo '<Files "db_env.php">'; \
    echo '  Require all denied'; \
    echo '</Files>'; \
    echo '<Files "helpers.php">'; \
    echo '  Require all denied'; \
    echo '</Files>'; \
    echo '<Files "bmn_db.sql">'; \
    echo '  Require all denied'; \
    echo '</Files>'; \
    echo '<Files "README_INSTALASI.txt">'; \
    echo '  Require all denied'; \
    echo '</Files>'; \
    } > /etc/apache2/conf-available/appsecurity.conf \
    && a2enconf appsecurity

# Pastikan folder upload foto bisa ditulis oleh Apache
RUN chown -R www-data:www-data /var/www/html/public/uploads

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8080

CMD ["docker-entrypoint.sh"]
