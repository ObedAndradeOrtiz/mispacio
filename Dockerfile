FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl unzip \
    libpq-dev \
    libzip-dev \
    libjpeg62-turbo-dev libpng-dev libwebp-dev libfreetype6-dev \
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j$(nproc) \
    pdo pdo_pgsql \
    bcmath \
    exif \
    gd \
    zip

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

# Si usas Vite (Laravel moderno)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
 && apt-get install -y nodejs \
 && npm install \
 && npm run build


CMD php -S 0.0.0.0:${PORT:-8080} -t public
