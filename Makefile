# Initial configurations
PHP_SERVICE := kariricode-contract
DC := docker-compose

# Command to execute commands inside the PHP container
EXEC_PHP := $(DC) exec -T php

# Icons
CHECK_MARK := ✅
WARNING := ⚠️
INFO := ℹ️ 

# Colors
RED := \033[0;31m
GREEN := \033[0;32m
YELLOW := \033[1;33m
NC := \033[0m # No Color

# Check if Docker is installed
CHECK_DOCKER := @command -v docker > /dev/null 2>&1 || { echo >&2 "${YELLOW}${WARNING} Docker is not installed. Aborting.${NC}"; exit 1; }
# Check if Docker Compose is installed
CHECK_DOCKER_COMPOSE := @command -v docker-compose > /dev/null 2>&1 || { echo >&2 "${YELLOW}${WARNING} Docker Compose is not installed. Aborting.${NC}"; exit 1; }
# Function to check if the container is running
CHECK_CONTAINER_RUNNING := @docker ps | grep $(PHP_SERVICE) > /dev/null 2>&1 || { echo >&2 "${YELLOW}${WARNING}  The container $(PHP_SERVICE) is not running. Run 'make up' to start it.${NC}"; exit 1; }
# Check if the .env file exists
CHECK_ENV := @test -f .env || { echo >&2 "${YELLOW}${WARNING}  .env file not found. Run 'make setup-env' to configure.${NC}"; exit 1; }

## setup-env: Copy .env.example to .env if the latter does not exist
setup-env:
	@test -f .env || (cp .env.example .env && echo "${GREEN}${CHECK_MARK} .env file created successfully from .env.example${NC}")

check-environment:
	@echo "${GREEN}${INFO} Checking Docker, Docker Compose, and .env file...${NC}"
	$(CHECK_DOCKER)
	$(CHECK_DOCKER_COMPOSE)
	$(CHECK_ENV)

check-container-running:
	$(CHECK_CONTAINER_RUNNING)

## up: Start all services in the background
up: check-environment
	@echo "${GREEN}${INFO} Starting services...${NC}"
	@$(DC) up -d
	@echo "${GREEN}${CHECK_MARK} Services are up!${NC}"

## down: Stop and remove all containers
down: check-environment
	@echo "${YELLOW}${INFO} Stopping and removing services...${NC}"
	@$(DC) down
	@echo "${GREEN}${CHECK_MARK} Services stopped and removed!${NC}"

## build: Build Docker images
build: check-environment
	@echo "${YELLOW}${INFO} Building services...${NC}"
	@$(DC) build
	@echo "${GREEN}${CHECK_MARK} Services built!${NC}"

## logs: Show container logs
logs: check-environment
	@echo "${YELLOW}${INFO} Container logs:${NC}"
	@$(DC) logs

## re-build: Rebuild and restart containers
re-build: check-environment
	@echo "${YELLOW}${INFO} Stopping and removing current services...${NC}"
	@$(DC) down
	@echo "${GREEN}${INFO} Rebuilding services...${NC}"
	@$(DC) build
	@echo "${GREEN}${INFO} Restarting services...${NC}"
	@$(DC) up -d
	@echo "${GREEN}${CHECK_MARK} Services rebuilt and restarted successfully!${NC}"
	@$(DC) logs

## shell: Access the shell of the PHP container
shell: check-environment check-container-running
	@echo "${GREEN}${INFO} Accessing the shell of the PHP container...${NC}"
	@$(DC) exec php sh

## composer-install: Install Composer dependencies. Use make composer-install [PKG="[vendor/package [version]]"] [DEV="--dev"]
composer-install: check-environment check-container-running
	@echo "${GREEN}${INFO} Installing Composer dependencies...${NC}"
	@if [ -z "$(PKG)" ]; then \
		$(EXEC_PHP) composer install; \
	else \
		$(EXEC_PHP) composer require $(PKG) $(DEV); \
	fi
	@echo "${GREEN}${CHECK_MARK} Composer operation completed!${NC}"

## composer-remove: Remove Composer dependencies. Usage: make composer-remove PKG="vendor/package"
composer-remove: check-environment check-container-running
	@if [ -z "$(PKG)" ]; then \
		echo "${RED}${WARNING}  You must specify a package to remove. Usage: make composer-remove PKG=\"vendor/package\"${NC}"; \
	else \
		$(EXEC_PHP) composer remove $(PKG); \
		echo "${GREEN}${CHECK_MARK} Package $(PKG) removed successfully!${NC}"; \
	fi

## composer-update: Update Composer dependencies
composer-update: check-environment check-container-running
	@echo "${GREEN}${INFO} Updating Composer dependencies...${NC}"
	$(EXEC_PHP) composer update
	@echo "${GREEN}${CHECK_MARK} Dependencies updated!${NC}"

## test: Run tests
test: check-environment check-container-running
	@echo "${GREEN}${INFO} Running tests...${NC}"
	$(EXEC_PHP) ./vendor/bin/phpunit --testdox --colors=always tests
	@echo "${GREEN}${CHECK_MARK} Tests completed!${NC}"

## test-file: Run tests on a specific class. Usage: make test-file FILE=[file]
test-file: check-environment check-container-running
	@echo "${GREEN}${INFO} Running test for class $(FILE)...${NC}"
	$(EXEC_PHP) ./vendor/bin/phpunit --testdox --colors=always tests/$(FILE)
	@echo "${GREEN}${CHECK_MARK} Test for class $(FILE) completed!${NC}"

## coverage: Run test coverage with visual formatting
coverage: check-environment check-container-running
	@echo "${GREEN}${INFO} Analyzing test coverage...${NC}"
	XDEBUG_MODE=coverage $(EXEC_PHP) ./vendor/bin/phpunit --coverage-text --colors=always tests | ccze -A

## coverage-html: Run test coverage and generate HTML report
coverage-html: check-environment check-container-running
	@echo "${GREEN}${INFO} Analyzing test coverage and generating HTML report...${NC}"
	XDEBUG_MODE=coverage $(EXEC_PHP) ./vendor/bin/phpunit --coverage-html ./coverage-report-html tests
	@echo "${GREEN}${INFO} Test coverage report generated in ./coverage-report-html${NC}"

## run-script: Run a PHP script. Usage: make run-script SCRIPT="path/to/script.php"
run-script: check-environment check-container-running
	@echo "${GREEN}${INFO} Running script: $(SCRIPT)...${NC}"
	$(EXEC_PHP) php $(SCRIPT)
	@echo "${GREEN}${CHECK_MARK} Script executed!${NC}"

## cs-check: Run PHP_CodeSniffer to check code style
cs-check: check-environment check-container-running
	@echo "${GREEN}${INFO} Checking code style...${NC}"
	$(EXEC_PHP) ./vendor/bin/php-cs-fixer fix --dry-run --diff
	@echo "${GREEN}${CHECK_MARK} Code style check completed!${NC}"

## cs-fix: Run PHP CS Fixer to fix code style
cs-fix: check-environment check-container-running
	@echo "${GREEN}${INFO} Fixing code style with PHP CS Fixer...${NC}"
	$(EXEC_PHP) ./vendor/bin/php-cs-fixer fix
	@echo "${GREEN}${CHECK_MARK} Code style fixed!${NC}"

## security-check: Check for security vulnerabilities in dependencies
security-check: check-environment check-container-running
	@echo "${GREEN}${INFO} Checking for security vulnerabilities with Security Checker...${NC}"
	$(EXEC_PHP) ./vendor/bin/security-checker security:check
	@echo "${GREEN}${CHECK_MARK} Security check completed!${NC}"

## quality: Run all quality commands
quality: check-environment check-container-running cs-check test security-check 
	@echo "${GREEN}${CHECK_MARK} All quality commands executed!${NC}"

## help: Show initial setup steps and available commands
help:
	@echo "${GREEN}Initial setup steps for configuring the project:${NC}"
	@echo "1. ${YELLOW}Initial environment setup:${NC}"
	@echo "   ${GREEN}${CHECK_MARK} Copy the environment file:${NC} make setup-env"
	@echo "   ${GREEN}${CHECK_MARK} Start the Docker containers:${NC} make up"
	@echo "   ${GREEN}${CHECK_MARK} Install Composer dependencies:${NC} make composer-install"
	@echo "2. ${YELLOW}Development:${NC}"
	@echo "   ${GREEN}${CHECK_MARK} Access the PHP container shell:${NC} make shell"
	@echo "   ${GREEN}${CHECK_MARK} Run a PHP script:${NC} make run-script SCRIPT=\"script_name.php\""
	@echo "   ${GREEN}${CHECK_MARK} Run the tests:${NC} make test"
	@echo "3. ${YELLOW}Maintenance:${NC}"
	@echo "   ${GREEN}${CHECK_MARK} Update Composer dependencies:${NC} make composer-update"
	@echo "   ${GREEN}${CHECK_MARK} Clear the application cache:${NC} make cache-clear"
	@echo "   ${RED}${WARNING}  Stop and remove all Docker containers:${NC} make down"
	@echo "\n${GREEN}Available commands:${NC}"
	@sed -n 's/^##//p' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ": "}; {printf "${YELLOW}%-30s${NC} %s\n", $$1, $$2}'

.PHONY: setup-env up down build logs re-build shell composer-install composer-remove composer-update test test-file coverage coverage-html run-script cs-check cs-fix security-check quality help
