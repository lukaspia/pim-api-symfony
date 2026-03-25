install:
	docker exec -it pim_php git config --global --add safe.directory /var/www/html
	docker exec -it pim_php composer install
	docker exec -it pim_php chmod -R +x bin/
	docker exec -it pim_php bin/console doctrine:database:create --if-not-exists
	docker exec -it pim_php bin/console doctrine:migrations:migrate --no-interaction
	docker exec -it pim_php bin/console doctrine:database:create --env=test --if-not-exists
	docker exec -it pim_php bin/console doctrine:migrations:migrate --env=test --no-interaction

migration:
	docker exec -it pim_php bin/console make:migration

migrate:
	docker exec -it pim_php bin/console doctrine:migrations:migrate --no-interaction
	docker exec -it pim_php bin/console doctrine:migrations:migrate --env=test --no-interaction

cache:
	docker exec -it pim_php bin/console cache:clear

test:
	docker exec -it pim_php bin/phpunit
