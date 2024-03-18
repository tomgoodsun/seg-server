#!/bin/sh
cd $(dirname $0)
./vendor/bin/phpcs ./app/**/*.php --standard=./tests/phpcs.xml
