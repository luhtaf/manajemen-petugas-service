FROM php:8.0

# Install system dependencies
RUN apt-get update -y && apt-get install -y \
    openssl \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo mbstring

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /app

# Copy application code
COPY . /app

# Install PHP dependencies
RUN composer install

# Expose port and start the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8181"]
EXPOSE 8181
