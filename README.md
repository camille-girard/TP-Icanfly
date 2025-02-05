# 🚀 ICanFly - Missions & Réservations

## **Projet Symfony 4IW2**

ICanFly est une application web de réservation de missions spatiales. 
Elle permet de consulter les prochaines missions, de réserver des places, procéder au paiement et de suivre les lancements/missions en direct.


---

## 📌 **Prérequis**
Avant de commencer, assurez-vous d'avoir ces outils installés :
- **[Docker](https://www.docker.com/get-started)** (avec Docker Compose)
- **[Composer](https://getcomposer.org/)** (pour gérer les dépendances PHP)

---

## **📂 Installation & Démarrage**

### **1️⃣ Cloner le projet**
```bash
git clone https://github.com/camille-girard/TP-Icanfly.git
```

### **2️⃣ Construire et Démarrer Docker**
Construisez et lancez les conteneurs pour la base de données et Symfony :
```bash
docker-compose build
docker-compose up -d
```
📌 Cela démarre **PostgreSQL** et **Symfony** en conteneurs.

### **3️⃣ Installer les dépendances Composer**
```bash
docker-compose exec php composer install
```

### **4️⃣ Appliquer la base de données**
```bash
docker-compose exec php bin/console doctrine:migrations:migrate
```

### **5️⃣ Charger les données de test (Utilisateur: alice)**
```bash
docker-compose exec php bin/console hautelook:fixtures:load
```
📌 **Cela ajoutera des missions spatiales et des réservations.**

---

## **🚀 Accéder à l'interface ICanFly**
Accédez au projet sur **[http://localhost](http://localhost)** 🌍

---

## **🛠 Accès à Adminer**

Adminer est accessible à l'adresse suivante :

🔗 **Adminer - Gestion de la BDD sur [http://localhost:8080/](http://localhost:8080/)**

**Paramètres de connexion PostgreSQL :**
- **Serveur :** database
- **Utilisateur :** app
- **Mot de passe :** `!ChangeMe!`
- **Base de données :** app

---

## **👥 Rôles & Comptes de Test**
Le projet dispose de **4 rôles utilisateurs** avec des comptes de test :

| Rôle      | Email                      | Mot de passe |
|-----------|----------------------------|-----------|
| **Admin** | catalinadanila6@gmail.com  | ESGI2024  |
| **User**  | bob.martin@example.com     | password123 |
| **Operator** | philippe.delente@gmail.com | MyGes24   |
| **Client**   | ntcami12@gmail.com | Client123 |

---

## **📱 Notifications Automatiques**

L'application envoie automatiquement des notifications par e-mail lorsque certaines actions sont effectuées :

- Lorsqu'une réservation est créée, modifiée ou annulée, l'utilisateur concerné reçoit un e-mail de confirmation.
- Lorsqu'une mission est mise à jour, les utilisateurs ayant réservé des places sont notifiés des modifications.
- Lorsqu'un profil utilisateur est modifié, un e-mail est envoyé pour informer des changements.

---

## **⚙️ Commandes Personnalisées**

Une commande personnalisée a été ajoutée pour envoyer des rappels de mission :

### **🛡️ `app:send-mission-reminder`**

Cette commande est exécutée automatiquement chaque jour à 08h via un **cron job**. Elle vérifie si des missions commencent dans les 24 heures suivantes et, si c'est le cas, elle envoie une notification aux utilisateurs ayant des réservations pour leur rappeler que leur mission approche et qu'ils doivent se préparer pour le décollage.

Commande manuelle :

```bash
php bin/console app:send-mission-reminder
```

---

## **📡 APIs Utilisées**

| API            | Utilisation                                 |
| -------------- | ------------------------------------------- |
| **SpaceX API**  | Récupération des données de lancement et missions spatiales |
| **YouTube API** | Intégration des vidéos en direct et archives des missions |
| **Stripe API**  | Gestion des paiements pour les réservations |

---

## **💳 Stripe (Paiement)**

### **Tester un paiement**
Utilisez une **carte de test Stripe** pour simuler un paiement :
- **Numéro** : `4242 4242 4242 4242`
- **Expiration** : `12/34`
- **CVC** : `123`
- **ZIP** : `75001`

---

## **📁 Structure du Projet**
```
/src
 ├── Command       # Commandes personnalisées
 ├── Controller    # Contrôleurs Symfony
 ├── Entity        # Entités Doctrine (BDD)
 ├── Enum          # Enumérations personnalisées
 ├── EventSubscriber # Hashage des mots de passe
 ├── Form          # Formulaires
 ├── Repository    # Requêtes personnalisées
 ├── Security      # Contrôleurs de sécurité & mailer
 ├── Service       # Services (APIs)
/templates         # Templates Twig
```

---

## **🔧 Commandes Utiles**
### **🎯 Développement**
```bash
docker compose exec php bash   # Pour ouvrir le container Docker et faire les commandes symfony
```

### **📊 Base de données**
```bash
php bin/console doctrine:migrations:migrate  # Appliquer les migrations
php bin/console hautelook:fixtures:load      # Charger les données de test
```

### **🛠️ Tests**
```bash
php bin/phpunit  # Exécuter les tests unitaires
```

---

## **📌 Auteurs**
👨‍🚀 **Philippe Delente** - Étudiant en développement  
👩‍🚀 **Catalina Danila** - Étudiant en développement  
👩‍🚀 **Camille Girard** - Étudiant en développement

---

### **✨ Bon voyage vers les étoiles avec ICanFly ! 🚀🌌**
Si vous avez des questions, n’hésitez pas à nous contacter ! 😃
