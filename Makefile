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