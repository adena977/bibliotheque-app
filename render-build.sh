#!/bin/bash

# Copier le bon fichier .env
cp .env.render .env

# Installer Composer si pas présent
if [ ! -f "composer.phar" ]; then
    curl -sS https://getcomposer.org/installer | php
fi

# Installer les dépendances
php composer.phar install --no-dev --optimize-autoloader

# Générer la clé
php artisan key:generate

# Créer la base SQLite
touch database/database.sqlite

# Exécuter les migrations
php artisan migrate --force

# Optimiser Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache