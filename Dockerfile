FROM php:8.2-apache

# ✅ อัปเดตและติดตั้ง libpq-dev สำหรับ PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install mysqli pdo_pgsql pgsql \
    && a2enmod rewrite

# ✅ ตั้ง DirectoryIndex ให้ Apache รู้จัก index.php
RUN echo "<IfModule dir_module>\n    DirectoryIndex index.php index.html\n</IfModule>" > /etc/apache2/mods-enabled/dir.conf

# ✅ คัดลอกไฟล์ทั้งหมดเข้า Apache root
COPY . /var/www/html/

# ✅ ตั้งสิทธิ์ให้ Apache
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# ✅ เปิดพอร์ต 80
EXPOSE 80
