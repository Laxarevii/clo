init: docker-down-clear docker-pull docker-build docker-up composer-install
up: docker-up
down: docker-down
restart: docker-down docker-up
test: composer-test
development-enable: composer-development-enable
development-disable: composer-development-disable
lint: api-lint
fix:
	docker-compose run --rm clo-php composer cs-fix

api-lint:
	docker-compose run --rm clo-php composer lint
	docker-compose run --rm clo-php composer cs-check

analyze:
	docker-compose run --rm clo-php composer psalm
docker-up:
	docker-compose up -d --build

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

composer-install:
	docker-compose exec clo-php composer install --no-plugins

composer-da:
	docker-compose exec clo-php composer dumpautoload

composer-test:
	docker-compose exec clo-php composer test
