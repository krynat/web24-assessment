#!/usr/bin/sh
set -e

until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1;
do
  sleep 1
done

echo "Create database if not exists"
php bin/console doctrine:database:create --if-not-exists

echo "Run Doctrine migrations"
php bin/console doctrine:migrations:migrate --no-interaction

echo "Add fixtures"
php bin/console doctrine:fixtures:load --no-interaction

exec "$@"