
# Wallstit

Une application qui vous permet de créer simplement des murs de post-its.

## Configuration

-Copier le .env en .env.local et configurez les variables.
-Copier le .env.test en .env.test.local et configurez les variables.
(attention le JWT_PASSPHRASE= doit être le même entre le .env.test.local et le .env.local)

## Testing

1) Faire l'étape "Configuration" du dessus.
2) Créer la base de données de test avec "php bin/console d:d:b --env=test".
3) Créer le schéma de la base de données avec "php bin/console doctrine:schema:update --env=test".
4) Lancer les fixtures pour mettre à jour la base de données de test avec "php bin/console --env=test doctrine:fixtures:load".
5) Pour tester, lancer la commande "php bin/phpunit" (et pour filter, ajouter "php bin/phpunit --filter UserRepositoryTest")

### Google oauth2

1) Pour configurer google oauth2, il faut dans un premier temps créer un projet depuis cet URL:
https://console.developers.google.com/apis/dashboard

2) Dans un second temps il faut activer "Google People API" pour pouvoir récupérer les informations de l'utilisateur

3) Configurer l'écran de consentement OAuth

4) Se rendre sur https://console.cloud.google.com/apis/credentials?project=wallstit et créer un nouvel identifiant oauth2 (Créer des identifiants -> ID client OAuth2)
Mettre le nom du projet: wallstit
Ajouter l'URL de redirection qui doit être https://127.0.0.1:8000/google-callback

5) Configurer le .env.local comme tel: 
###> Google oauth2 ###
GOOGLE_OAUTH2_SECRET=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
GOOGLE_OAUTH2_CLIENT_ID=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX.apps.googleusercontent.com
GOOGLE_OAUTH2_REDIRECT_URI=https://127.0.0.1:8000/google-callback
###< Google oauth2 ###

### Lexik/JWT

1) Avoir installé l'extension PHP extension=sodium

2) Et générer les clés ainsi que la passphrase:
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=YOUR_STRONG_PASSPHRASE
###< lexik/jwt-authentication-bundle ###

Puis executer la commande suivante pour générer les clés: php bin/console lexik:jwt:generate-keypair

### Mise en prod

Cloner le projet sur la machine (idéalement dans /var/www/) avec git: clone https://github.com/BaptisteLT/Wallstit
Une fois le projet installé il faudra créer un .env.local et mettre en mode prod

Installer docker sur le serveur Linux (https://docs.docker.com/engine/install/debian/)
Ensuite run: docker-compose -f docker-compose.prod.yaml up -d --build

Puis installer les dépendances PHP en faisant les commandes suivantes:
1) docker-compose -f docker-compose.prod.yaml exec php81-service /bin/bash
2) cd ..
3) cd project
4) composer install

Puis installer les packages et build le javascript:
docker-compose -f docker-compose.prod.yaml run --rm node-service npm install --production
et 
docker-compose -f docker-compose.prod.yaml run --rm node-service npm run build