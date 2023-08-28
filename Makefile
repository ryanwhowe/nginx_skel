
docker_up:
	@echo "Starting Docker"
	@(cd devops/redmine_dev && docker-compose up -d)

docker_bash:
	@echo "Starting a bash prompt on the php container"
	@(cd devops/redmine_dev && docker-compose exec php bash)

docker_down:
	@echo "Stopping Docker"
	@(cd devops/redmine_dev && docker-compose down)

build:
	@echo "Build Production Image"
	@docker build --no-cache -f devops/images/redmine_php_prod.Dockerfile -t redmine_monitor:latest .