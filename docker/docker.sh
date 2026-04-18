#!/bin/bash

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_ROOT="$( cd "$SCRIPT_DIR/.." && pwd )"

docker_cd() {
cd "$PROJECT_ROOT/docker" || exit
}

docker_status() {
echo -e "${BLUE}Container Status:${NC}"
docker_cd
docker compose ps
}

docker_up() {
echo -e "${BLUE}Starting containers...${NC}"
docker_cd
docker compose up -d
sleep 2
docker_status
}

docker_build() {
echo -e "${BLUE}Building images...${NC}"
docker_cd
docker compose build
echo -e "${GREEN}✅ Images built${NC}"
}

docker_build_up() {
echo -e "${BLUE}Building and starting containers...${NC}"
docker_cd
docker compose up -d --build --force-recreate
sleep 2
docker_status
}

docker_restart() {
echo -e "${YELLOW}Restarting containers...${NC}"
docker_cd
docker compose restart
sleep 2
docker_status
}

docker_restart_nginx() {
echo -e "${YELLOW}Restarting nginx...${NC}"
docker_cd
docker compose restart nginx
}

docker_down() {
echo -e "${YELLOW}Stopping containers...${NC}"
docker_cd
docker compose down
}

docker_logs() {
docker_cd
docker compose logs -f --tail=100
}

docker_logs_app() {
docker_cd
docker compose logs -f --tail=100 app
}

docker_logs_mysql() {
docker_cd
docker compose logs -f --tail=100 mysql
}

docker_exec_app() {
docker_cd
docker compose exec app sh
}

docker_exec_mysql() {
docker_cd
docker compose exec -T app mysql -h mysql -u abelo_user -ppassword abelo --ssl-mode=DISABLED
}

docker_composer() {
docker_cd
docker compose exec -T app composer "$@"
}

docker_npm() {
docker_cd
docker compose exec -T app npm "$@"
}

docker_permissions() {
docker_cd
docker compose exec app chown -R www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
}

docker_migrate() {
echo -e "${BLUE}Running migrations...${NC}"
docker_cd
docker compose exec -T app php /var/www/html/bin/artisan migrate
echo -e "${GREEN}✅ Migrations completed${NC}"
}

docker_seed() {
echo -e "${BLUE}Running seeders...${NC}"
docker_cd
docker compose exec -T app php /var/www/html/bin/artisan seed
echo -e "${GREEN}✅ Seeders completed${NC}"
}

docker_migrate_seed() {
echo -e "${BLUE}Running migrations and seeders...${NC}"
docker_migrate
docker_seed
echo -e "${GREEN}✅ Database setup completed${NC}"
}

docker_migrate_fresh_seed() {
echo -e "${YELLOW}Running migrate:fresh --seed...${NC}"
docker_cd
echo -e "${BLUE}Running migrations with --fresh...${NC}"
docker compose exec -T app php bin/artisan migrate --fresh
docker_seed
echo -e "${GREEN}✅ Database refreshed and seeded${NC}"
}

docker_clean() {
echo -e "${RED}WARNING: This will remove all containers and volumes!${NC}"
read -p "Are you sure? (yes/no): " -r
if [[ $REPLY =~ ^[Yy][Ee][Ss]$ ]]; then
docker_cd
docker compose down -v
fi
}

case "${1:-help}" in
up) docker_up ;;
down) docker_down ;;
build) docker_build ;;
build-up) docker_build_up ;;
restart) docker_restart ;;
restart-nginx) docker_restart_nginx ;;
status) docker_status ;;
logs) docker_logs ;;
logs-app) docker_logs_app ;;
logs-mysql) docker_logs_mysql ;;
exec-app) docker_exec_app ;;
exec-mysql) docker_exec_mysql ;;
composer) shift; docker_composer "$@" ;;
npm) shift; docker_npm "$@" ;;
migrate) docker_migrate ;;
seed) docker_seed ;;
migrate-seed|--seed) docker_migrate_seed ;;
migrate-fresh-seed|migrate-fresh|fresh-seed) docker_migrate_fresh_seed ;;
permissions) docker_permissions ;;
clean) docker_clean ;;
*) echo "Unknown command"; exit 1 ;;
esac
