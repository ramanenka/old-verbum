#!/usr/bin/env bash

repo=/var/repo/verbum.git
cd $repo
#composer install --no-dev

git --work-tree=/var/www/html/slounik --git-dir=$repo checkout -f production
