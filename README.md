# Gestion de serveurs Web en PHP/Symfony

## Architecture
![Architecture](doc/architecture.jpg?raw=true "Architecture")

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

### Utilisateur de test pour le frontend :
http://localhost:8088

- Nom : user1/ Mot de passe : user1
