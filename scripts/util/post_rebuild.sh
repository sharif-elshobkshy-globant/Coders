#!/usr/bin/env bash
echo "Post Rebuild Setup..."
cd /app

apt-get update
apt-get install vim -y
composer install
