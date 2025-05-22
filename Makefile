up:
	docker-compose up -d --build

down:
	docker-compose down

restart:
	docker-compose down && docker-compose up -d --build

logs:
	docker-compose logs -f

bash:
	docker exec -it visitor-app bash

reverb:
	docker exec -it visitor-reverb bash

migrate:
	docker exec visitor-app php artisan migrate

seed:
	docker exec visitor-app php artisan db:seed

fresh:
	docker exec visitor-app php artisan migrate:fresh --seed

npm:
	docker exec visitor-app npm install

composer:
	docker exec visitor-app composer install

test:
	docker exec visitor-app php artisan test