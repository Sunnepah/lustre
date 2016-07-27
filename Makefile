all: clean test

test:
	vendor/bin/phpunit tests

coverage:
	vendor/bin/phpunit --coverage-html=artifacts/coverage

view-coverage:
	open artifacts/coverage/index.html

clean:
	rm -rf artifacts/*
