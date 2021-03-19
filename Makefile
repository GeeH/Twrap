.PHONY: test psalm coverage

test:
	./vendor/bin/pest

psalm:
	./vendor/bin/psalm

coverage:
	XDEBUG_MODE=coverage ./vendor/bin/pest --coverage

all: psalm coverage
