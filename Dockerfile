FROM php:8.2-apache

# ✅ ติดตั้ง Dependency สำหรับ MySQL + PostgreSQL และเปิด mod_rewrite
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install mysqli pdo_pgsql pgsql \
    && a2enmod rewrite

# ✅ ตั้ง DirectoryIndex ให้ Apache รู้จัก index.php
RUN echo "DirectoryIndex index.php" > /etc/apache2/conf-enabled/directoryindex.conf

# ✅ คัดลอกโค้ดทั้งหมดเข้า Apache Root
COPY . /var/www/html/

# ✅ ตั้งสิทธิ์ไฟล์ให้ Apache อ่านเขียนได้
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# ✅ เปิดพอร์ต 80 ให้เว็บเซิร์ฟเวอร์ทำงาน
EXPOSE 80
