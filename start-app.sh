#!/bin/bash
docker-compose down --remove-orphans
docker-compose up -d
docker exec -d $(docker ps --filter="name=todocqrses_app" -q) cron
docker exec -d $(docker ps --filter="name=todocqrses_app" -q) crontab /etc/cron.d/app
docker-compose logs -f