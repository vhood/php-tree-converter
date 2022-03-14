project = php-array-helper

help:
	@make --help

init: build stop run composer-install

build:
	@docker build -t ${project} .

remove:
	@docker rm ${project}

test:
	@docker exec -u 1000 ${project} vendor/bin/phpunit --colors="always"

run:
	@docker run --rm -d -it --name ${project} -v ${shell pwd}:/app ${project}

stop:
	@docker stop ${project}

composer-install:
	@docker exec -u 1000 ${project} composer install

shell:
	@docker exec -it -u 1000 ${project} /bin/bash
