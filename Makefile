up:
	docker-compose up -d --build

down:
	docker-compose down

restart:
	make down && make up

bash:
	docker-compose exec php bash

migrate:
	docker-compose exec php php bin/console doctrine:migrations:migrate

test:
	docker-compose exec php ./vendor/bin/phpunit
