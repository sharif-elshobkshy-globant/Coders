#!/usr/bin/env bash
PROJECT_ROOT=/app/docroot

# Synchronize the drupal 8 instance

echo "Sync and updating drupal site"
if [ -d $PROJECT_ROOT ]
  then
    cd /app
    composer install
    cd $PROJECT_ROOT
    drush cim -y
    drush updatedb -y
    drush cr
fi
