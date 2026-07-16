#!/bin/bash
set -e

# Railway menyediakan variabel PORT secara dinamis. Default ke 8080 jika tidak ada (mis. saat build lokal).
PORT="${PORT:-8080}"

sed -ri "s/Listen [0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground
