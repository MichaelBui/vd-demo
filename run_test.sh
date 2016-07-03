#!/usr/bin/env bash
cd "$(dirname "$0")" && \
docker-compose -f docker-compose.test.yml -p vd-test down; \
docker-compose -f docker-compose.test.yml -p vd-test run --rm phpunit; \
docker-compose -f docker-compose.test.yml -p vd-test down;
