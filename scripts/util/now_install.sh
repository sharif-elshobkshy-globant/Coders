#!/usr/bin/env bash
echo "Installing Drupal..."
cd /app

# Uncomment these lines for dropping and creating the database.
echo "Clean Database"
yes | mysqladmin drop  database -u dev -h mysql -pdev
mysqladmin create database -u dev -h mysql -pdev

echo "Composer install..."
composer install

PROJECT_ROOT=/app/docroot
echo "Performing new D8 installation"
cd $PROJECT_ROOT

# Prepare the installation
chmod -R 777 $PROJECT_ROOT/sites/default
#echo "Deleting current settings config"
rm -f $PROJECT_ROOT/sites/default/settings.php

drush si config_installer config_installer_sync_configure_form.sync_directory=../config/sync --db-url=mysql://dev:dev@mysql/database --account-name=admin --account-pass=admin -y
#drush si standard --db-url=mysql://dev:dev@mysql/database --account-name=admin --account-pass=admin -y

echo "Obtaining and setting the site UUID"
line=$(head -n 1 /app/config/sync/system.site.yml);
uuid=$(echo $line | sed 's/uuid: //g');
echo "UUID:" $uuid

drush config-set "system.site" uuid "$uuid" --y;

# Change the permissions again since drupal at this step changed the permissions.
chmod -R 777 $PROJECT_ROOT/sites/default

# Copy all the settings
echo "Moving settings.php file to .../sites/default/..."
rm -f $PROJECT_ROOT/sites/default/settings.php
cp $PROJECT_ROOT/sites/default/base.settings.php $PROJECT_ROOT/sites/default/settings.php

chmod 775 $PROJECT_ROOT/sites/default

echo "Configuring congifuration synchronization folder permissions..."

echo "Importing configurations"
now=$(date +"%Y_%m_%d_%H_%M_%S");
drush cr
