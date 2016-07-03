#!/usr/bin/env bash
cd "$(dirname "$0")" && \
sh -c "docker-compose -f docker-compose.dev.yml -p vd-dev $@"
