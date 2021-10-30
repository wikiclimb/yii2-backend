![WikiClimb logo](./static/img/wikiclimb-logo.png)

# WikiClimb backend

This repository contains a Yii2/php backend for WikiClimb.

# Testing

## Running tests locally

In an environment with docker installed and a local mysql client, it is enough to clone the repository and run:

```shell
docker-compose up -d
php init --env=Test --overwrite=All
./yii_test migrate --interactive=0
vendor/bin/codecept run
docker-compose down
```
