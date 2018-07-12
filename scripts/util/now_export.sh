#!/usr/bin/env bash
PROJECT_ROOT=/app/docroot
echo "Exporting config"
if [ -d $PROJECT_ROOT ]
  then
    cd $PROJECT_ROOT
    drush cex -y
    chmod -R 755 /app/config
fi
