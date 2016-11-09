#!/bin/bash


bin/console assets:install --symlink
#app/console assetic:dump
#app/console cache:clear --env=prod
bin/console cache:clear