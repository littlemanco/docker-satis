# Task runner

.PHONY: help build

.DEFAULT_GOAL := help

SHELL := /bin/bash

# http://stackoverflow.com/questions/1404796/how-to-get-the-latest-tag-name-in-current-branch-in-git
APP_VERSION  := $(shell git describe --abbrev=0)
GIT_HASH     := $(shell git rev-parse --short HEAD)

ANSI_TITLE        := '\e[1;32m'
ANSI_CMD          := '\e[0;32m'
ANSI_TITLE        := '\e[0;33m'
ANSI_SUBTITLE     := '\e[0;37m'
ANSI_WARNING      := '\e[1;31m'
ANSI_OFF          := '\e[0m'

PATH_DOCS                := $(shell pwd)/docs
PATH_BUILD_CONFIGURATION := $(shell pwd)/build

TIMESTAMP := $(shell date "+%s")

help: ## Show this menu
	@echo -e $(ANSI_TITLE)Satis$(ANSI_OFF)$(ANSI_SUBTITLE)" - Run Satis on Docker"$(ANSI_OFF)
	@echo -e $(ANSI_TITLE)Commands:$(ANSI_OFF)
	@grep -E '^[a-zA-Z_-%]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "    \033[32m%-30s\033[0m %s\n", $$1, $$2}'

clean: ## Delete everything
	rm -rf app/vendor app/bin

container: ## ${TAG} | Build the container
	[ ! -z "${TAG}" ] && { echo "You need to supply the 'TAG' variable"; exit; }
	docker build -t quay.io/littlemanco/satis:${TAG} .

application: ## Build the satis application
	cd app && composer install \
	    --ignore-platform-reqs \
	    --no-dev

push: build ## ${TAG} | Push the container to the registry
	docker push quay.io/lttlemanco/satis:${TAG}
