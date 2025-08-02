FROM php:8.2-apache

# ติดตั้ง PostgreSQL + MySQL driver
RUN apt-get update && apt-get install -y \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pdo_pgsql pgsql mysqli \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# คัดลอกไฟล์ทั้งหมดไปไว้ใน Apache root
COPY . /var/www/html/

# ตั้ง working dir
WORKDIR /var/www/html/

# รัน Apache
CMD ["apache2-foreground"]
