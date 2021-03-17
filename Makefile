.PHONY: test psalm

test:
	./vendor/bin/pest

psalm:
	./vendor/bin/psalm

coverage:
	XDEBUG_MODE=coverage ./vendor/bin/pest --coverage
