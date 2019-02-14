#/bin/bash

cd frontend

# build
docker-compose build

# install
docker-compose exec -u www-data web composer install


# env
if [ ! -f .env ]; then
    cp .env.dist .env
fi

cd ../api
npm install
