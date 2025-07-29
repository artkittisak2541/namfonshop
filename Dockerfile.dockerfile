FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pdo_pgsql pgsql mysqli

COPY . /var/www/html/

WORKDIR /var/www/html/

# ✅ รัน SQL script ก่อนรัน apache
CMD bash -c 'psql "host=$PGHOST dbname=$PGDATABASE user=$PGUSER password=$PGPASSWORD port=5432" -f init_postgresql.sql || true && apache2-foreground'
