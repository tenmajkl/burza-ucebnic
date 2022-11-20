install:
	cp .env.example .env
	composer install
	yarn
	yarn run mix
