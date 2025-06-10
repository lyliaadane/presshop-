#!/bin/bash

# Lancer le serveur Symfony en arrière-plan
php -S 0.0.0.0:8080 -t public &

# Lancer le worker Messenger
php bin/console messenger:consume async --limit=1000 --time-limit=3600
