project = php-array-helper

help:
	@make --help

build:
	@docker build -t ${project} .

remove:
	@docker rm ${project}

test:
	@pwd

up:
	@docker run --rm -d -it --name ${project} -v ${shell pwd}:/var/www/html ${project}

down:
	@docker stop ${project}

shell:
	@docker exec -it -u 1000 ${project} /bin/bash
