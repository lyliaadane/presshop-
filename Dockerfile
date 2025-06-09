# Dockerfile
FROM php:8.2-cli

# Installe les dépendances
RUN apt-get update && apt-get install -y unzip zip git curl libicu-dev libonig-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql opcache

# Installe Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copie les fichiers
WORKDIR /app
COPY . /app

#  Créer le dossier d'uploads et régler les permissions
RUN mkdir -p public/uploads/images \
 && chown -R www-data:www-data public/uploads/images \
 && chmod -R 775 public/uploads/images

# Installe les dépendances Symfony sans exécuter les scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts

EXPOSE 8080

# Démarre le serveur Symfony
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
