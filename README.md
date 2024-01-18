
# Wallstit

Une application qui vous permet de créer simplement des murs de post-its.

## Configuration

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

Avoir installé l'extension PHP extension=sodium

