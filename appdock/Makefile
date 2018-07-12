myARGS = $(filter-out $@,$(MAKECMDGOALS))
MAKEFLAGS += --silent

list:
	sh -c "echo; $(MAKE) -p no_targets__ | awk -F':' '/^[a-zA-Z0-9][^\$$#\/\\t=]*:([^=]|$$)/ {split(\$$1,A,/ /);for(i in A)print A[i]}' | grep -v '__\$$' | grep -v 'Makefile'| sort"

#############################
# Access to the environment
#############################

root:
	docker exec -it -u root $$(docker-compose ps -q app) /bin/bash

#############################
# New CMS
#############################
cms-install:
	bash bin/now.sh install

cms-update:
	bash bin/now.sh sync

cms-export:
	bash bin/now.sh export

#############################
# Docker machine states
#############################

rebuild:
	docker-compose stop
	docker-compose rm --force app
	docker-compose build --no-cache
	docker-compose up -d --remove-orphans
	bash bin/now.sh post-rebuild

#############################
# Argument fix workaround
#############################
%:
	@:
