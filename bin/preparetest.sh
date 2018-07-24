#!/usr/bin/env bash

./bin/console doctrine:database:drop -n --force
./bin/console doctrine:database:create -n
./bin/console doctrine:migration:migrate -n
./bin/console doctrine:fixtures:load -n

./bin/phpunit