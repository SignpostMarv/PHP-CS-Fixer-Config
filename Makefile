DOCKER_IMAGE_PREFIX ?= signpostmarv/php-cs-fixer-config

PHP_TASK = @docker run --rm -it \
		-u $(shell id -u):$(shell id -g) \
		-v $(shell pwd)/:/app \
		-w /app/ \
		${DOCKER_IMAGE_PREFIX}--$(PHP)

build:
	@docker build -t ${DOCKER_IMAGE_PREFIX}--php7 - < php7.Dockerfile
	@docker build -t ${DOCKER_IMAGE_PREFIX}--php80 - < php8.0.Dockerfile
	@docker build -t ${DOCKER_IMAGE_PREFIX}--php81 - < php8.1.Dockerfile
	@docker build -t ${DOCKER_IMAGE_PREFIX}--php82 - < php8.2.Dockerfile

--install:
	$(PHP_TASK) \
		composer install

install--php7: PHP=php7
install--php7: --install

install--php80: PHP=php80
install--php80: --install

install--php81: PHP=php81
install--php81: --install

install--php82: PHP=php82
install--php82: --install

--tests:
	@docker run --rm -it \
		-u $(shell id -u):$(shell id -g) \
		-v $(shell pwd)/:/app \
		-w /app/ \
		${DOCKER_IMAGE_PREFIX}--$(PHP) \
		composer run tests

tests--php7: PHP=php7
tests--php7: --tests

tests--php80: PHP=php80
tests--php80: --tests

tests--php81: PHP=php81
tests--php81: --tests

tests--php82: PHP=php82
tests--php82: --tests

psalm--set-baseline: PHP=php7
psalm--set-baseline:
	${PHP_TASK} ./vendor/bin/psalm --set-baseline=./psalm.baseline.xml
