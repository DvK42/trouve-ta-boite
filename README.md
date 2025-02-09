
# Trouve ta Boîte

Trouve ta Boîte est une plateforme de gestion des offres de stage et d'alternance, permettant aux entreprises de publier des offres et aux étudiants de postuler facilement.

---

## **Prérequis**

Avant de commencer, assurez-vous d’avoir installé :

- **Docker** : https://docs.docker.com/get-docker/
- **Docker Compose** : https://docs.docker.com/compose/install/
- **Composer** : https://getcomposer.org/
- **Symfony CLI (facultatif)** : https://symfony.com/download

---

## **Installation locale avec Docker**

### 1. **Cloner le projet**
```bash
git clone https://github.com/votre-utilisateur/trouve-ta-boite.git
cd trouve-ta-boite
```

### 2. **Configurer les variables d'environnement**
Copiez le fichier **`.env.local.example`** et renommez-le en **`.env.local`** :
```bash
cp .env.local.example .env.local
```

Modifiez les variables si nécessaire :
```env
DATABASE_URL="mysql://app:ChangeThisPassword@database:3306/app?serverVersion=8.0?charset=utf8mb4"
MAILER_DSN=smtp://mailer:1025
APP_ENV=dev
```

---

### 3. **Lancer les conteneurs Docker**
```bash
docker-compose up -d
```

Les services suivants seront lancés :
- **PHP + Composer** : application web
- **MySQL** : base de données
- **Mailpit** : service mail pour la réception des emails de développement

---

### 4. **Installer les dépendances**
```bash
docker exec trouve-ta-boite-php-1 composer install
```

---

### 5. **Installer les packages**
```bash
docker exec trouve-ta-boite-php-1 npm install
```

---

### 6. **Initialiser la base de données + Fixtures**
```bash
docker exec trouve-ta-boite-php-1 php bin/console doctrine:database:create
docker exec trouve-ta-boite-php-1 php bin/console doctrine:migrations:migrate
docker exec trouve-ta-boite-php-1 php bin/console doctrine:fixtures:load
```

---

### 7. **Build l'application**
```bash
docker exec trouve-ta-boite-php-1 npm run build
```

---

### 8. **Lancer le serveur Symfony**
```bash
docker exec -it trouve-ta-boite-php-1 symfony server:start
```

Accédez à l’application via : [http://localhost:8000](http://localhost:8000)

---

## **Connexion**

### 1. **Étudiant**
Email : ```student@example.com```
Mot de passe : ```student123```

### 2. **Entreprise**
Email : ```company@example.com```
Mot de passe : ```company123```

### 3. **Administrateur**
Email : ```admin@example.com```
Mot de passe : ```admin123```

---

## **Tests**

### 1. **Exécuter les tests PHPUnit**
```bash
docker exec trouve-ta-boite-php-1 vendor/bin/phpunit
```

---

## **Fonctionnalités principales**
- Gestion des entreprises et des offres.
- Postulation des étudiants.
- Notifications par email via Mailpit.
- Interface backend pour les utilisateurs authentifiés + Administration.

---

## **Utilisation de Mailpit**

Mailpit est disponible sur [http://localhost:8025](http://localhost:8025). 

---

## **Structure du projet**

- **`src/`** : Contient les classes principales de l'application.
- **`tests/`** : Contient les tests unitaires et fonctionnels.
- **`public/`** : Contient les ressources publiques et le point d'entrée (`index.php`).

---

## **Dépannage**

### **Problèmes courants :**
1. **Erreur de connexion à la base de données :**
   - Vérifiez les variables dans **`.env.local`**.
   - Assurez-vous que le service MySQL est bien lancé : 
     ```bash
     docker ps
     ```

2. **Les emails ne sont pas envoyés :**
   - Assurez-vous que Mailpit est actif : [http://localhost:8025](http://localhost:8025).

3. **Composer ne fonctionne pas :**
   - Nettoyez les dépendances locales :
     ```bash
     docker exec trouve-ta-boite-php-1 composer install --no-cache
     ```