#!/usr/bin/env bash

composer install --no-dev

rm -r ./node_modules
NODE_ENV=production npm install
./node_modules/.bin/grunt
