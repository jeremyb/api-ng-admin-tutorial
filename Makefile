install:
	cd client && bower install
	cd server && composer install && app/console doctrine:schema:create && app/console doctrine:fixtures:load -n

