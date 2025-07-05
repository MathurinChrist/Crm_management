# Crm_management_front
This project is going to maanage the front of the web application
cd backend-symfony

# Installer les dépendances PHP
composer install

# Configurer l'environnement
cp .env .env.local
# Modifier les paramètres de connexion à la base de données dans .env.local

# Créer la base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Générer les clés JWT
php bin/console lexik:jwt:generate-keypair

# Lancer le serveur Symfony
symfony serve
