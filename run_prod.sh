#!/usr/bin/env bash
cd "$(dirname "$0")" && \
sh -c "docker-compose -f docker-compose.prod.yml -p vd-prod $@"
