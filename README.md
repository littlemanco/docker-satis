# Satis Docker Imagea

## Why

Todo: Some notes on that satis requires people to ssh and rebuild, and I don't like that very much. It's also
cron-able, but I don't like that much either. Making a HTTP request seems like a good solution, as that's quite trivial
to do up in a crontab somewhere or from a git hook.

## Usage

The docker file expects a satis.json file mounted at /etc/satis/satis.json. A default file exists if there is none
mounted in that gives an example of the expected structure of this file.

### Configuring HTTPS

Todo: Think of this

### Configuring Basic Auth

Todo: Think of this

### Using a private key

Todo: Think of this
