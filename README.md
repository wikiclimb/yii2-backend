# WikiClimb backend

![codeception tests workflow](https://github.com/wikiclimb/yii2-backend/actions/workflows/codecept_tests.yml/badge.svg)

---

![WikiClimb logo](./static/img/wikiclimb-logo-150.png)

WikiClimb's PHP backend.

## About WikiClimb

To learn more about WikiClimb and its mission, visit the [organization's profile page](https://github.com/wikiclimb).

## About this repository

This repository contains a Yii2/PHP multi-tier application that serves as WikiClimb's backend.

Each tier does the following:

- _api_: code that is not specific to a particular version of the API.
- _apiv1_: code that maybe updated with an API version update.
- _backend_: this tier does not do much currently, in the future it could serve as an admin console.
- _common_: code that is common between all tiers, most commonly configuration and models.
- _console_: code that gets called from the console. Maintenance scripts, migrations...
- _frontend_: code that needs to run on the web and it is better served from outside an application, password recovery,
  privacy polity, documentation...

Contributions are welcome.

# Running the project locally

Make sure that:

- Git is installed
- PHP8 is installed. The system runs in a container with PHP8 but it will be easier to install the dependencies if
  composer can access it.
- You need to be able to run composer commands
- You need to be able to run docker and docker-compose commands from the terminal

Clone the repository

```shell
git clone https://github.com/wikiclimb/yii2-backend.git
```

Navigate to the root of the project and install the composer dependencies, for example using composer.phar. (PHP >= 8.0
and a copy of composer.phar needed)

```shell
php composer.phar install
```

Start the docker containers.

```shell
docker-compose up -d
```

Initialize the environment, for example to Development, it could also be Test or Production.

```shell
php init --env=Development --overwrite=All
```

Note that for production environments, you need to manually configure the application with the correct web server,
database server and credentials, before you can use it.

If you want to use the debugger you will also need to configure your IP as allowed.

Configuration for the test and development environment, using the docker setup should be in place after running the
command.

Run the database migrations.

```shell
./yii migrate –interactive=false
./yii_test migrate --interactive=false
```

Execute the tests.

```shell
vendor/bin/codecept run
```

That is all, happy development!

![WikiClimb logo](./static/img/wikiclimb-logo.png)
