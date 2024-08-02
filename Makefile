build:
	docker-compose down && \
	CP src/.env.example src/.env && \
	docker-compose build
up:
	docker-compose up -d
down:
	docker-compose down
ps:
	docker ps
shell:
	docker exec -it my_app bash
