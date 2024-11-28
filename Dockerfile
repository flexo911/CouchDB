# Використовуємо базовий образ PHP з Apache
FROM php:8.1-apache

# Встановлюємо необхідні розширення PHP
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    curl \
    && docker-php-ext-install curl

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Копіюємо файли вашого проекту до контейнера
COPY . /var/www/html

# Налаштуємо права на папку
RUN chown -R www-data:www-data /var/www/html

# Активуємо конфігурацію Apache
RUN a2enmod rewrite
RUN a2ensite 000-default


# Активуємо модулі Apache
RUN a2enmod proxy proxy_http
CMD ["apache2-foreground"]


COPY apache-config/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN service apache2 restart