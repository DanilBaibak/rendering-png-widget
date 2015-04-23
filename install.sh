#!/bin/sh
green='\033[0;32m'
NC='\033[0m' # No Color

echo -e "\n\n${green}Install Composer${NC}\n"
curl -sS https://getcomposer.org/installer | php

echo -e "\n\n${green}Install dependencies using Composer${NC}\n"
php composer.phar install

echo -e "\n\n${green}Run migration${NC}\n"
app/console doctrine:migrations:migrate