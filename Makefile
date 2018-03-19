# linux workaround
##################
export HOST_USER_ID=$(shell id -u)


# docker commands
#################
COMPOSE_RUN        = docker-compose -f docker/docker-compose.yml run --rm
RUN_IN_DOCKER_BASH = $(COMPOSE_RUN) -e HOST_USER_ID=$(HOST_USER_ID) php bash -c


# default task
##############
default: install


# install task
##############
install: composer_install composer_autoload


# composer
##########
composer_install:
	$(RUN_IN_DOCKER_BASH) "composer install"

composer_update:
	$(RUN_IN_DOCKER_BASH) "composer update"

composer_autoload:
	$(RUN_IN_DOCKER_BASH) "composer dump-autoload"


# run tests
###########
test:
	@echo "+++ Run unit tests (with coverage) +++"
	$(COMPOSE_RUN) -e HOST_USER_ID=$(HOST_USER_ID) php vendor/bin/phpunit -c phpunit.xml --testsuite unit $(ARGS)

