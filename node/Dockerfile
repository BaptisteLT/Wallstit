RUN apt update && apt install -yqq nodejs npm

COPY . /var/www/project

# Utilisez l'image Node.js officielle
FROM node:latest

# Définit le répertoire de travail dans le conteneur
WORKDIR /var/www/project

# Copie le fichier package.json et package-lock.json dans le conteneur
#COPY package*.json ./
#COPY webpack.config.js ./
#COPY src/ ./src/
COPY . . 
 
# Exécute npm install pour installer les dépendances
RUN npm install

RUN npm run build

# Copie tout le reste du code source dans le conteneur
COPY . .
