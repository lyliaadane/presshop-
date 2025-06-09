# Installer Node.js et npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
  && apt-get install -y nodejs

# Copier package.json et package-lock.json
COPY package*.json ./

# Installer les dépendances npm
RUN npm install

# Copier le reste des fichiers
COPY . .

# Lancer la build JS
RUN npm run build

# Installer les dépendances PHP sans dev et sans scripts (ou avec scripts si tu préfères)
RUN composer install --no-dev --optimize-autoloader
