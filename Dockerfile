FROM php:8.2-apache

# ติดตั้ง PostgreSQL client และ PHP extension
RUN apt-get update && apt-get install -y libpq-dev git unzip \
    && docker-php-ext-install pdo_pgsql pgsql

# เปิด mod_rewrite (ถ้าใช้ .htaccess)
RUN a2enmod rewrite

# ✅ Redirect error log ไป stdout/stderr
RUN echo "ErrorLog /proc/self/fd/2" >> /etc/apache2/apache2.conf

# ✅ คัดลอกโค้ดทั้งหมด
COPY . /var/www/html/

# ✅ คัดลอกซ้ำเผื่อ Git ไม่ push
COPY init_db_once.php /var/www/html/
