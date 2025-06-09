FROM php:8.2-cli

# Installer dépendances système + Node.js + npm
RUN apt-get update && apt-get install -y unzip zip git curl libicu-dev libonig-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql opcache \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copier package.json et package-lock.json pour cache Docker efficace
COPY package*.json ./

# Installer dépendances npm
RUN npm install

# Copier tout le reste des fichiers de l’application
COPY . .

# Construire les assets frontend avec Webpack Encore (npm run build doit exister dans package.json)
RUN npm run build

# Installer les dépendances PHP sans dev et sans scripts automatiques
RUN composer install --no-dev --optimize-autoloader --no-scripts

EXPOSE 8080

# Démarrer le serveur PHP interne
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
