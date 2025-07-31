FROM php:8.2-apache

# نصب mysqli
RUN docker-php-ext-install mysqli

# کپی کردن فایل‌های پروژه به مسیر HTML آپاچی
COPY . /var/www/html/

EXPOSE 80
