name := laravel.test
sail := ./vendor/bin/sail
exec := exec $(name)

docker:
	docker run --rm -u "$(id -u):$(id -g)" -v $(pwd):/var/www/html -w /var/www/html laravelsail/php81-composer:latest; composer install --ignore-platform-reqs

up:
	$(sail) up -d

build:
	$(sail) build

composer:
	$(sail) composer install -o

install: docker up build composer preparedb

down:
	$(sail) down

stop:
	$(sail) stop

bash:
	$(sail) $(exec) bash

test:
	$(sail) $(exec) php artisan test --parallel --coverage

restart: down install

preparedb:
	$(sail) php artisan cache:forget spatie.permission.cache; $(sail) artisan migrate --seed

seed:
	$(sail) artisan migrate --seed
preparedbtest:
	$(sail) artisan migrate --env=testing

