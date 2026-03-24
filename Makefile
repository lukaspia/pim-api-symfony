migration:
	docker exec -it pim_php bin/console make:migration

migrate:
	docker exec -it pim_php bin/console doctrine:migrations:migrate --no-interaction
	docker exec -it pim_php bin/console doctrine:migrations:migrate --env=test --no-interaction

cache:
	docker exec -it pim_php bin/console cache:clear

test:
	docker exec -it pim_php bin/phpunit
