#!/usr/bin/env bash

set -o pipefail  # trace ERR through pipes
set -o errtrace  # trace ERR through 'time command' and other functions
set -o nounset   ## set -u : exit the script if you try to use an uninitialised variable
set -o errexit   ## set -e : exit the script if any statement returns a non-true return value

CMS_SCRIPTS=scripts/util

source "$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/.config.sh"
case "$1" in
    ###################################
    ## Install an empty database for Coders Drupal 8 CMS
    ###################################
    "install")
        if [[ -n "$(dockerContainerId app)" ]]; then
            source "$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/../etc/environment.yml"
            dockerExec /bin/bash "$CMS_SCRIPTS/now_install.sh"
        fi
        ;;
    ###################################
    ## Build the drupal project based on the make files.
    ###################################
    "make")
        if [[ -n "$(dockerContainerId app)" ]]; then
            dockerExec /bin/bash "$CMS_SCRIPTS/now_build.sh"
            pwd
        fi
        ;;
    ###################################
    ## Synchronize the drupal site
    ###################################
    "sync")
        if [[ -n "$(dockerContainerId app)" ]]; then
            dockerExec /bin/bash "$CMS_SCRIPTS/now_sync.sh"
            pwd
        fi
        ;;
    ###################################
    ## Export config files
    ###################################
    "export")
        if [[ -n "$(dockerContainerId app)" ]]; then
            dockerExec /bin/bash "$CMS_SCRIPTS/now_export.sh"
            pwd
        fi
        ;;
    ###################################
    ## Post rebuild actions
    ###################################
    "post-rebuild")
        if [[ -n "$(dockerContainerId app)" ]]; then
            dockerExec /bin/bash "$CMS_SCRIPTS/post_rebuild.sh"
            pwd
        fi
        ;;
esac
