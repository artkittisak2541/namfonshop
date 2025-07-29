FROM php:8.2-apache

# ✅ ติดตั้ง PostgreSQL และ MySQL Extension
RUN apt-get update && apt-get install -y \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pdo_pgsql pgsql mysqli

# ✅ คัดลอกโค้ดเว็บทั้งหมด
COPY . /var/www/html/

# ✅ ตั้ง working directory
WORKDIR /var/www/html/

# ✅ เริ่ม Apache ปกติ
CMD ["apache2-foreground"]
