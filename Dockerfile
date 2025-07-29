FROM php:8.2-apache

# ติดตั้ง dependency พื้นฐาน รวมถึง libpq-dev สำหรับ PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    zip \
    && docker-php-ext-install mysqli pdo_pgsql pgsql \
    && a2enmod rewrite

# ให้ Apache เริ่มต้นที่ index.php
RUN echo "<IfModule dir_module>\n    DirectoryIndex index.php index.html\n</IfModule>" > /etc/apache2/mods-enabled/dir.conf
