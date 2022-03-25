include .env

help:
	@make --help

init: envirenments build run composer-install

restart: stop run composer-install

envirenments:
	@cp .env-default .env

build:
	@docker build -t ${PROJECT_NAME} .

remove:
	@docker rm ${PROJECT_NAME}

test:
	@docker exec -u 1000 ${PROJECT_NAME} vendor/bin/phpunit --colors="always"

run:
	@docker run --rm -d -it --name ${PROJECT_NAME} -v ${shell pwd}:/app ${PROJECT_NAME}

stop:
	@docker stop ${PROJECT_NAME}

composer-install:
	@docker exec -u 1000 ${PROJECT_NAME} composer install

shell:
	@docker exec -it -u 1000 ${PROJECT_NAME} /bin/bash
