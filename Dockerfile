FROM php:8.2-apache

# ✅ ติดตั้ง PostgreSQL + PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pdo_pgsql pgsql mysqli

# ✅ คัดลอกไฟล์ไปยังโฟลเดอร์เว็บไซต์
COPY . /var/www/html/

# ✅ ตั้ง working directory
WORKDIR /var/www/html/

# ✅ รัน SQL init แล้วเริ่ม Apache
CMD bash -c '\
echo "➡️ Running init_postgresql.sql..." && \
psql "host=$PGHOST dbname=$PGDATABASE user=$PGUSER password=$PGPASSWORD port=5432" -f init_postgresql.sql || true && \
apache2-foreground'
