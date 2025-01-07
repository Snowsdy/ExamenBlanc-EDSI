# Utilisation de l'image officielle PHP avec Apache
FROM php:8.1-apache

# Installation des extensions nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql mysqli

# Activation de mod_rewrite pour Apache (utile pour les URL conviviales)
RUN a2enmod rewrite

# Définir le répertoire de travail dans le conteneur
WORKDIR /var/www/html

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Changer les permissions des fichiers pour Apache
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80 pour accéder au serveur Apache
EXPOSE 80

# Démarrer Apache au lancement du conteneur
CMD ["apache2-foreground"]
