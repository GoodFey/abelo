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

docker_down() {
    echo -e "${YELLOW}Stopping containers...${NC}"
    docker_cd
    docker compose down
    echo -e "${GREEN}✅ Containers stopped${NC}"
}

docker_logs() {
    echo -e "${BLUE}Application logs:${NC}"
    docker_cd
    docker compose logs -f app
}

docker_shell() {
    echo -e "${BLUE}Opening PHP container shell...${NC}"
    docker_cd
    docker compose exec app sh
}

docker_mysql() {
    echo -e "${BLUE}Connecting to MySQL...${NC}"
    docker_cd
    docker compose exec mysql mysql -u abelo_user -ppassword abelo
}

docker_help() {
    echo -e "${BLUE}Abelo Docker Commands:${NC}"
    echo ""
    echo "  docker.sh up          - Start containers"
    echo "  docker.sh down        - Stop containers"
    echo "  docker.sh build       - Build images"
    echo "  docker.sh rebuild     - Build and start containers"
    echo "  docker.sh status      - Show container status"
    echo "  docker.sh logs        - View application logs"
    echo "  docker.sh shell       - Open PHP container shell"
    echo "  docker.sh mysql       - Connect to MySQL"
    echo "  docker.sh help        - Show this help message"
    echo ""
}

case "$1" in
    up)
        docker_up
        ;;
    down)
        docker_down
        ;;
    build)
        docker_build
        ;;
    rebuild)
        docker_build_up
        ;;
    status)
        docker_status
        ;;
    logs)
        docker_logs
        ;;
    shell)
        docker_shell
        ;;
    mysql)
        docker_mysql
        ;;
    help)
        docker_help
        ;;
    *)
        echo -e "${RED}Unknown command: $1${NC}"
        echo ""
        docker_help
        exit 1
        ;;
esac
