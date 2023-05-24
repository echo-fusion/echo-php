build:
	docker-compose build
up:
	docker-compose up -d
down:
	docker-compose down
ps:
	docker ps
shell:
	docker exec -it my_app bash
