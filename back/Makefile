.PHONY: migrate-fresh-testing
migrate-fresh-testing:
	php artisan migrate:fresh --env=testing --seed

.PHONY: test
test:
	php artisan test

.PHONY: key-generate
key-generate:
	php artisan key:generate

.PHONY: action-install
action-install:
	composer install --no-ansi --no-interaction --no-scripts --no-suggest --no-progress --prefer-dist
