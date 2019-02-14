#/bin/bash

cd frontend

docker-compose build
docker-compose up -d
docker-compose exec -u www-data web composer install

# env
if [ ! -f .env ]; then
    cp .env.dist .env
fi

cd ../api
npm install
port=4545 node index.js
