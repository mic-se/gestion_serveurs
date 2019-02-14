#/bin/bash

if ! [ -x "$(command -v docker)" ]; then
  echo 'Error: docker is not installed.' >&2
  exit 1
fi

if ! [ -x "$(command -v node)" ]; then
  echo 'Error: node is not installed.' >&2
  exit 1
fi

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
