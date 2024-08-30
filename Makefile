install:
	cp .env.example .env
	composer install
	yarn
	yarn run mix

update:
	git pull
	composer update
	yarn
	yarn mix
