# Gestion de serveurs Web en PHP/Symfony

## Prérequis
Docker et node doivent être installés

## Installation (Ubuntu, Debian)
### Activer Docker API
```bash
sudo nano /lib/systemd/system/docker.service
```

Remplacer la ligne ExecStart... par :

```bash
ExecStart=/usr/bin/dockerd -H fd:// -H tcp://127.0.0.1:5555
```

```bash
sudo systemctl daemon-reload
sudo systemctl restart docker
```

### Installer l'application

```bash
./install.sh
```

### Configurer l'application
Modifier la ligne : API_URL=http://VOTRE_IP_LOCALE:3000

### Lancer l'application

```bash
docker-compose -f frontend/docker-compose.yml up -d
node api/index.js
```

### Utilisateur de test pour le frontend :
http://localhost:8080

- Nom : user1/ Mot de passe : user1
