# Dockerfile
FROM php:8.2-apache

# ติดตั้ง PostgreSQL client และ PHP extension
RUN apt-get update && apt-get install -y libpq-dev git unzip && docker-php-ext-install pdo_pgsql pgsql

# คัดลอกไฟล์เว็บไซต์
COPY . /var/www/html/

# ตั้งค่า permission
RUN chown -R www-data:www-data /var/www/html/

# ✅ ไม่ต้อง COPY ไป docker-entrypoint-initdb.d เพราะไม่ได้ใช้ PostgreSQL container
# แนะนำให้ใช้ init.php เรียก SQL แทน
