COMPOSER_VENDOR_PATH		:= ./vendor
COMPOSER_VENDOR_BIN_PATH	:= $(COMPOSER_VENDOR_PATH)/bin

LOCAL_HOST					:= 127.0.0.1
LOCAL_PORT					:= 8000

PROJECT_PATH				:= .

APP_ENTRYPOINT				:= $(PROJECT_PATH)/public/index.php


install:
	composer install --no-dev
	cp -n .env.prod .env

develop:
	composer install
	cp -n .env.develop .env

autoload:
	composer dump-autoload

test: autoload
	$(COMPOSER_VENDOR_BIN_PATH)/phpunit

server_dev:
	php -S $(LOCAL_HOST):$(LOCAL_PORT) $(APP_ENTRYPOINT)


### Travis CI ###

travis_before_install:
	composer self-update
	# Setup env file
	cp .env.test .env

travis_install:
	composer install --prefer-dist --no-interaction

travis_test_cov:
	vendor/bin/phpunit --coverage-clover=coverage.xml

travis_codecov: SHELL:=/bin/bash
travis_codecov:
	bash <(curl -s https://codecov.io/bash)
