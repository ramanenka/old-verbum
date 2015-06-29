#!/usr/bin/env bash

composer install --no-dev

git --work-tree=/var/www/html/slounik --git-dir=$repo checkout -f production
