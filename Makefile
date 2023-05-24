build:
	docker-compose down && \
	CP src/.env.example src/.env && \
	docker-compose build && \
	docker-compose up -d && \
	docker exec -it my_app composer install
up:
	docker-compose up -d
down:
	docker-compose down
ps:
	docker ps
shell:
	docker exec -it my_app bash
