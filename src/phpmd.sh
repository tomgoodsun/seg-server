#!/bin/sh
cd $(dirname $0)
./vendor/bin/phpmd ./app/**/*.php text tests/phpmd.xml
