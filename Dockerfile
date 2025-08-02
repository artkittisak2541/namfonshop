# Dockerfile
FROM php:8.2-apache

# ติดตั้ง PostgreSQL client และ PHP extension
RUN apt-get update && apt-get install -y libpq-dev git unzip && docker-php-ext-install pdo_pgsql pgsql

# คัดลอกโค้ดทั้งหมด
COPY . /var/www/html/

# ✅ คัดลอกเฉพาะไฟล์ที่สำคัญอีกครั้ง (เผื่อ Git ไม่ push บางไฟล์)
COPY init_db_once.php /var/www/html/


# ✅ ไม่ต้อง COPY ไป docker-entrypoint-initdb.d เพราะไม่ได้ใช้ PostgreSQL container
# แนะนำให้ใช้ init.php เรียก SQL แทน
