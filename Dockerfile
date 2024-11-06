#Image de officiel Php avec Apache
FROM php:8.1-apache

#Installer l'extension PDO mysql
RUN docker-php-ext-install pdo pdo_mysql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www


#Copier les fichiers de ton application dans le conteneur
COPY . /var/www 



#Exposer le port 80 pour Apache
EXPOSE 80

